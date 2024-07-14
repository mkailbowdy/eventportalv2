<?php

use App\Livewire\Homepage;
use App\Livewire\Termsandconditions;
use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::view('/maps', 'maps');
Route::get('/', Homepage::class);
Route::get('/rules', Termsandconditions::class);

//Route::get('/testroute', function () {
//    $name = "Funny Coder";
//    Mail::to('myhkail.mendoza@gmail.com')->send(new MyTestEmail($name));
//});
