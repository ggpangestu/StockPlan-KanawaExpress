<?php

namespace App\Http\Controllers\Armada;

use App\Http\Controllers\Controller;
use App\Models\ArmadaSession;
use App\Models\ReturnCheck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function index(): View
    {
        $activeSession = ArmadaSession::with(['items.finishedGood.menu'])
            ->where('armada_user_id', Auth::id())
            ->where('status', ArmadaSession::STATUS_ACTIVE)
            ->latest()
            ->first();

        $recentSessions = ArmadaSession::with(['items.finishedGood.menu'])
            ->where('armada_user_id', Auth::id())
            ->where('status', ArmadaSession::STATUS_FINISHED)
            ->latest('finished_at')
            ->take(8)
            ->get();

        return view(
            'armada.sessions.index',
            compact('activeSession', 'recentSessions')
        );
    }

    public function updateSold(Request $request, ArmadaSession $session): RedirectResponse
    {
        abort_unless($session->armada_user_id === Auth::id(), 403);
        abort_unless($session->status === ArmadaSession::STATUS_ACTIVE, 403);

        $validated = $request->validate([
            'sold' => ['required', 'array'],
            'sold.*' => ['required', 'integer', 'min:0'],
        ]);

        $this->saveSoldQuantities($session, $validated['sold']);

        return back()->with('success', 'Sold quantities updated.');
    }

    public function finish(Request $request, ArmadaSession $session): RedirectResponse
    {
        abort_unless($session->armada_user_id === Auth::id(), 403);
        abort_unless($session->status === ArmadaSession::STATUS_ACTIVE, 403);

        $validated = $request->validate([
            'sold' => ['required', 'array'],
            'sold.*' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($session, $validated) {
            $this->saveSoldQuantities($session, $validated['sold']);

            $session->load('items');

            foreach ($session->items as $item) {
                $soldQuantity = $item->quantity_sold;
                $returnedQuantity = $item->quantity_sent - $soldQuantity;

                $item->update([
                    'quantity_returned' => $returnedQuantity,
                ]);

                if ($returnedQuantity > 0) {
                    ReturnCheck::create([
                        'armada_session_item_id' => $item->id,
                        'finished_good_id' => $item->finished_good_id,
                        'armada_user_id' => $session->armada_user_id,
                        'quantity' => $returnedQuantity,
                    ]);
                }
            }

            $session->update([
                'status' => ArmadaSession::STATUS_FINISHED,
                'finished_at' => now(),
            ]);
        });

        return redirect()
            ->route('armada.sessions.index')
            ->with('success', 'Session finished. Unsold products were sent to Produksi for checking.');
    }

    private function saveSoldQuantities(ArmadaSession $session, array $soldQuantities): void
    {
        $session->load('items');

        foreach ($session->items as $item) {
            $soldQuantity = (int) ($soldQuantities[$item->id] ?? 0);

            if ($soldQuantity > $item->quantity_sent) {
                throw ValidationException::withMessages([
                    "sold.{$item->id}" => 'Sold quantity cannot be greater than sent quantity.',
                ]);
            }

            $item->update([
                'quantity_sold' => $soldQuantity,
            ]);
        }
    }
}
