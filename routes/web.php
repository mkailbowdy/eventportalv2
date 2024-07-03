<?php

use App\Livewire\Events\ListEvents;
use Illuminate\Support\Facades\Route;

Route::get('/', ListEvents::class);
