<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\SuperAdminMiddleware;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::view('/maps', 'maps');
