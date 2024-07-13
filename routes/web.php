<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Homepage;
use App\Livewire\Termsandconditions;
use Illuminate\Support\Facades\Route;

Route::view('/maps', 'maps');
Route::get('/', Homepage::class);
Route::get('/rules', Termsandconditions::class);
