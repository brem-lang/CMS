<?php

use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Livewire\About;
use App\Livewire\HomePage;
use App\Livewire\Shop;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class)->name('home');

Route::get('/shop', Shop::class)->name('shop');

Route::get('/product/{id}', ViewProduct::class)->name('product.view');

Route::get('/about', About::class)->name('about');

// Route to serve private storage files
Route::get('/storage/private/{path}', function ($path) {
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);

    return response($file, 200)
        ->header('Content-Type', $mimeType);
})->where('path', '.*');
