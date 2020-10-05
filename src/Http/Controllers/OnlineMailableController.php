<?php

namespace Sammyjo20\Jockey\Http\Controllers;

use Illuminate\Http\Request;
use Sammyjo20\Jockey\Models\OnlineMailable;

class OnlineMailableController
{
    /**
     * @param $onlineMailableUuid
     * @param Request $request
     * @return mixed
     */
    public function view($onlineMailableUuid, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        // Todo: Attempt route model binding here. I tried and it didn't work... 🤔

        $onlineMailable = OnlineMailable::where('uuid', $onlineMailableUuid)
            ->firstOrFail();

        // I did create an exception, OnlineMailablePendingException which
        // returns a nice message saying that the mailable is still being
        // generated - but I decided not to use it because it could cause
        // confusion if the mailable is deleted prior to expiry date.

        return response($onlineMailable->content, 200)
            ->header('Content-Type', 'text/html');
    }
}
