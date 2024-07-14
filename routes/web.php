<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Homepage;
use App\Livewire\Termsandconditions;
use App\Mail\MyTestEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::view('/maps', 'maps');
Route::get('/', Homepage::class);
Route::get('/rules', Termsandconditions::class);
//Route::get('/testroute', Homepage::class);


Route::get('/testroute', function () {
    $name = "Funny Coder";
    Mail::to('myhkail.mendoza@gmail.com')->send(new MyTestEmail($name));
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
