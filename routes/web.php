<?php

use Illuminate\Support\Facades\Route;
use Sammyjo20\Jockey\Http\Controllers\ViewOnlineMailableController;
use \Illuminate\Routing\Middleware\SubstituteBindings;

Route::get('/mail/view-online/{onlineMailable}', ViewOnlineMailableController::class)
    ->middleware(SubstituteBindings::class)
    ->name('mail.view-online');
