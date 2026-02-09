<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories\Index as CategoriesIndex;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::view('/items', 'items.index')->name('items.index');
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
});


require __DIR__.'/settings.php';
