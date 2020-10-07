<?php

use Illuminate\Support\Facades\Route;
use Sammyjo20\Wagonwheel\Http\Controllers\ViewOnlineMailableController;
use \Illuminate\Routing\Middleware\SubstituteBindings;

Route::get('/mail/view-online/{onlineMailable:uuid}', ViewOnlineMailableController::class)
    ->middleware(SubstituteBindings::class)
    ->name('mail.view-online');
