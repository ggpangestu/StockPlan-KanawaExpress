<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ArmadaSession;
use App\Models\FinishedGood;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ArmadaSessionController extends Controller
{
    public function index(): View
    {
        $armadas = User::where('role', 'armada')->orderBy('name')->get();

        $availableGoods = FinishedGood::with('menu')
            ->where('status', FinishedGood::STATUS_AVAILABLE)
            ->where('current_quantity', '>', 0)
            ->whereDate('expired_date', '>=', today())
            ->orderBy('expired_date')
            ->get();

        $activeSessions = ArmadaSession::with(['armada', 'allocator', 'items.finishedGood.menu'])
            ->where('status', ArmadaSession::STATUS_ACTIVE)
            ->latest()
            ->get();

        $recentSessions = ArmadaSession::with(['armada', 'items.finishedGood.menu'])
            ->where('status', ArmadaSession::STATUS_FINISHED)
            ->latest('finished_at')
            ->take(10)
            ->get();

        return view(
            'owner.armada-sessions.index',
            compact('armadas', 'availableGoods', 'activeSessions', 'recentSessions')
        );
    }

    public function live(): JsonResponse
    {
        $sessions = ArmadaSession::with(['armada', 'items.finishedGood.menu'])
            ->where('status', ArmadaSession::STATUS_ACTIVE)
            ->latest()
            ->get()
            ->map(function (ArmadaSession $session) {
                $items = $session->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => $item->finishedGood->menu->name,
                        'batch' => 'PRD-' . str_pad($item->finishedGood->production_id, 4, '0', STR_PAD_LEFT),
                        'expired_date' => $item->finishedGood->expired_date->format('d M Y'),
                        'quantity_sent' => $item->quantity_sent,
                        'quantity_sold' => $item->quantity_sold,
                        'remaining' => max($item->quantity_sent - $item->quantity_sold, 0),
                    ];
                });

                return [
                    'id' => $session->id,
                    'armada' => $session->armada->name,
                    'started_at' => $session->started_at?->format('d M Y H:i'),
                    'total_sent' => $items->sum('quantity_sent'),
                    'total_sold' => $items->sum('quantity_sold'),
                    'total_remaining' => $items->sum('remaining'),
                    'items' => $items->values(),
                ];
            });

        return response()->json([
            'sessions' => $sessions,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'armada_user_id' => ['required', 'exists:users,id'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $armada = User::whereKey($validated['armada_user_id'])
            ->where('role', 'armada')
            ->firstOrFail();

        $items = collect($validated['items'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Choose at least one product to allocate.',
            ]);
        }

        DB::transaction(function () use ($items, $validated, $armada) {
            $hasActiveSession = ArmadaSession::where('armada_user_id', $armada->id)
                ->where('status', ArmadaSession::STATUS_ACTIVE)
                ->lockForUpdate()
                ->exists();

            if ($hasActiveSession) {
                throw ValidationException::withMessages([
                    'armada_user_id' => "{$armada->name} already has an active session.",
                ]);
            }

            $session = ArmadaSession::create([
                'armada_user_id' => $armada->id,
                'allocated_by' => Auth::id(),
                'session_date' => today(),
                'status' => ArmadaSession::STATUS_ACTIVE,
                'started_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $finishedGoodId => $quantity) {
                $finishedGood = FinishedGood::with('menu')
                    ->whereKey($finishedGoodId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $finishedGood->status !== FinishedGood::STATUS_AVAILABLE ||
                    $finishedGood->current_quantity < $quantity ||
                    $finishedGood->expired_date->lt(today())
                ) {
                    throw ValidationException::withMessages([
                        "items.{$finishedGoodId}" => "{$finishedGood->menu->name} is no longer available in that quantity.",
                    ]);
                }

                $finishedGood->current_quantity -= $quantity;

                if ($finishedGood->current_quantity === 0) {
                    $finishedGood->status = FinishedGood::STATUS_EMPTY;
                }

                $finishedGood->save();

                $session->items()->create([
                    'finished_good_id' => $finishedGood->id,
                    'quantity_sent' => $quantity,
                ]);
            }
        });

        return redirect()
            ->route('owner.armada-sessions.index')
            ->with('success', 'Session allocated to Armada.');
    }

}
