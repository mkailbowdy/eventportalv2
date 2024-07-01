<?php

use App\Livewire\EventIndex;
use Illuminate\Support\Facades\Route;

Route::get('/events', EventIndex::class)->name('events.index');
