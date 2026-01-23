<?php

use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::redirect('/app', '/admin');

Route::get('/', HomePage::class);
