<?php

namespace Sammyjo20\Wagonwheel\Http\Controllers;

use Illuminate\Http\Request;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;

class ViewOnlineMailableController
{
    public function __invoke(Request $request, OnlineMailable $onlineMailable)
    {
        if (! $request->hasValidSignature()) {
            abort(404);
        }

        return response($onlineMailable->content, 200)
            ->header('Content-Type', 'text/html');
    }
}
