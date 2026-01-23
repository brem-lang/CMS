<?php

use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class);

// Route to serve private storage files
Route::get('/storage/private/{path}', function ($path) {
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);
    
    return response($file, 200)
        ->header('Content-Type', $mimeType);
})->where('path', '.*');
