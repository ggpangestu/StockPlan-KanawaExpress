<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Owner\ProductionController as OwnerProductionController;
use App\Http\Controllers\Owner\FinishedGoodController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
  
    //RAW MATERIAL
    Route::prefix('owner')
        ->name('owner.')
        ->middleware('role:owner')
        ->group(function () {

        Route::resource('raw-materials', RawMaterialController::class);

        Route::post(
            'raw-materials/{rawMaterial}/restock',
            [RawMaterialController::class, 'storeRestock']
        )->name('raw-materials.store-restock');

        Route::post(
            'raw-materials/{rawMaterial}/adjustment',
            [RawMaterialController::class, 'storeAdjustment']
        )->name('raw-materials.store-adjustment');

        Route::patch(
            'raw-material-transactions/{transaction}/price',
            [RawMaterialController::class, 'updateRestockPrice']
        )->name('raw-materials.update-restock-price');

        Route::patch(
            'raw-materials/{rawMaterial}/toggle-active',
            [RawMaterialController::class, 'toggleActive']
        )->name('raw-materials.toggle-active');
        
        // MENUS
        Route::resource('menus', MenuController::class);
        Route::patch(
            'menus/{menu}/toggle-active', 
            [MenuController::class, 'toggleActive']
            )->name('menus.toggle-active');

        // PRODUCTIONS
        Route::resource('productions', OwnerProductionController::class);

        // STOK JADI (FINISHED GOODS)
        Route::get('stok-jadi', [FinishedGoodController::class, 'index'])->name('stok-jadi.index');
        
    });

    Route::get('/owner/armada', function () {return view('owner.kelola-armada');})->name('owner.armada');

    
    // ROUTE UNTUK TIM DAPUR / PRODUKSI
    Route::prefix('produksi')
    ->name('produksi.')
    ->middleware('role:produksi') // Aktifkan nanti jika role sudah siap
    ->group(function () {
        
        // Memakai ProductionController murni (Milik Dapur)
        Route::get('productions', [ProductionController::class, 'index'])->name('productions.index');
        Route::get('productions/{production}', [ProductionController::class, 'show'])->name('productions.show');
        
        // Action Buttons
        Route::patch('productions/{production}/start', [ProductionController::class, 'start'])->name('productions.start');
        Route::post('productions/{production}/complete', [ProductionController::class, 'complete'])->name('productions.complete');
    });

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';