<?php

use App\Livewire\About;
use App\Livewire\Blog;
use App\Livewire\Contact;
use App\Livewire\HomePage;
use App\Livewire\ReturnAndRefund;
use App\Livewire\Shop;
use App\Livewire\ViewBlog;
use App\Livewire\ViewProduct;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class)->name('home');

Route::get('/shop', Shop::class)->name('shop');

Route::get('/product/{id}', ViewProduct::class)->name('product.view');

Route::get('/about', About::class)->name('about');

Route::get('/contact', Contact::class)->name('contact');

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog/{id}', ViewBlog::class)->name('blog.view');

Route::get('/return-and-refund', ReturnAndRefund::class)->name('return-and-refund');

// Route to serve private storage files
Route::get('/storage/private/{path}', function ($path) {
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);

    return response($file, 200)
        ->header('Content-Type', $mimeType);
})->where('path', '.*');
