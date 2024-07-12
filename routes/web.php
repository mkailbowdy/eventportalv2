<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Homepage;
use Illuminate\Support\Facades\Route;

Route::view('/maps', 'maps');
Route::get('/', Homepage::class);
