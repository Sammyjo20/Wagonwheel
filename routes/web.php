<?php

use Illuminate\Support\Facades\Route;
use Sammyjo20\Jockey\Http\Controllers\ViewOnlineMailableController;

Route::get('/mail/view-online/{onlineMailable:uuid}', ViewOnlineMailableController::class)->name('mail.view-online');
