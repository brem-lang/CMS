<?php

use App\Livewire\About;
use App\Livewire\Blog;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\HomePage;
use App\Livewire\ReturnAndRefund;
use App\Livewire\Shop;
use App\Livewire\ViewBlog;
use App\Livewire\ViewCart;
use App\Livewire\ViewProduct;
use Illuminate\Http\Request;
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

Route::get('/view-cart', ViewCart::class)->name('view-cart');

Route::get('/checkout', Checkout::class)->name('checkout');

// Cashier checkout for payment
// Route::post('/checkout/create', function (Request $request) {
//     $user = $request->user();
//     $totalAmount = $request->input('total_amount', 3000);

//     return $user->checkout([
//         [
//             'price_data' => [
//                 'currency' => 'php',
//                 'unit_amount' => $totalAmount,
//                 'product_data' => [
//                     'name' => 'Total Order Payment',
//                 ],
//             ],
//         ],
//     ], [
//         'mode' => 'payment',
//         'success_url' => route('home').'?paid=true',
//         'cancel_url' => route('checkout').'?paid=false',
//     ]);
// })->middleware('auth')->name('checkout.create');

// Authentication routes (using Breeze's secure authentication)
require __DIR__.'/auth.php';

// Route to serve private storage files
Route::get('/storage/private/{path}', function ($path) {
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);

    return response($file, 200)
        ->header('Content-Type', $mimeType);
})->where('path', '.*');
