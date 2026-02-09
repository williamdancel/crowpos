<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Pos\Receipt;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::view('pos', 'pos')
    ->middleware(['auth', 'verified'])
    ->name('pos');


Route::middleware(['auth'])->group(function () {
    Route::view('/items', 'items.index')->name('items.index');
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/pos', PosIndex::class)->name('pos.index');
    Route::get('/pos/receipt/{sale}', Receipt::class)->name('pos.receipt');
});


require __DIR__.'/settings.php';
