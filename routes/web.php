<?php

use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::redirect('/admin', '/admin');

Route::get('/', HomePage::class);
