<?php

namespace Sammyjo20\Jockey\Http;

use Illuminate\Support\Facades\Route;
use Sammyjo20\Jockey\Http\Controllers\OnlineMailableController;

Route::get('/mail/view-online/{onlineMailable}', [OnlineMailableController::class, 'view'])->name('mail.view-online');
