<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Pos\Receipt;
use App\Livewire\Sales\Index as SalesIndex;

Route::get('/', fn () => view('landing'))->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/pos', PosIndex::class)->name('dashboard');

    Route::view('/items', 'items.index')->name('items.index');
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/pos/receipt/{sale}', Receipt::class)->name('pos.receipt');
    Route::get('/sales', SalesIndex::class)->name('sales.index');
});

require __DIR__.'/settings.php';