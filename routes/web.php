<?php

use App\Livewire\About;
use App\Livewire\Blog;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\DigitalProducts;
use App\Livewire\HomePage;
use App\Livewire\ReturnAndRefund;
use App\Livewire\Shop;
use App\Livewire\ViewBlog;
use App\Livewire\ViewCart;
use App\Livewire\ViewDigitalProduct;
use App\Livewire\ViewProduct;
use Illuminate\Support\Facades\Route;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class)->name('home');

Route::get('/shop', Shop::class)->name('shop');

Route::get('/digital-products', DigitalProducts::class)->name('digital-products');
Route::get('/digital-products/{id}/download', [\App\Http\Controllers\DigitalProductDownloadController::class, '__invoke'])->name('digital-product.download');
Route::get('/digital-products/{id}', ViewDigitalProduct::class)->name('digital-product.view');

Route::get('/product/{id}', ViewProduct::class)->name('product.view');

Route::get('/about', About::class)->name('about');

Route::get('/contact', Contact::class)->name('contact');

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog/{id}', ViewBlog::class)->name('blog.view');

Route::get('/return-and-refund', ReturnAndRefund::class)->name('return-and-refund');

Route::get('/view-cart', ViewCart::class)->name('view-cart');

Route::get('/orders', \App\Livewire\Orders::class)->name('orders')->middleware('auth');
Route::get('/orders/{id}', \App\Livewire\OrderDetail::class)->name('order.detail')->middleware('auth');

Route::get('/track-order', [\App\Http\Controllers\TrackOrderController::class, 'index'])->name('track-order');
Route::post('/track-order', [\App\Http\Controllers\TrackOrderController::class, 'search'])->name('track-order.search');

Route::get('/checkout', Checkout::class)->name('checkout')->middleware('throttle:10,1');

Route::get('/checkout/success/{order}', \App\Livewire\CheckoutSuccess::class)->name('checkout.success');
Route::get('/checkout/failed/{order}', \App\Livewire\CheckoutFailed::class)->name('checkout.failed');
Route::get('/checkout/bank-transfer/{order}', \App\Livewire\BankTransferInstructions::class)->name('checkout.bank-transfer');
Route::post('/webhook', [\App\Http\Controllers\PayMongoWebhookController::class, 'handle'])->name('webhook');

// Authentication routes (using Breeze's secure authentication)
require __DIR__.'/auth.php';
