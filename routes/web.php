<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Routing\Middleware\SubstituteBindings;
use Sammyjo20\Wagonwheel\Http\Controllers\ViewOnlineMailableController;

Route::get('/mail/view-online/{onlineMailable:uuid}', ViewOnlineMailableController::class)
    ->middleware(SubstituteBindings::class)
    ->name('mail.view-online');
