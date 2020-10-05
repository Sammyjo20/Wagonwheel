<?php

namespace Sammyjo20\Jockey\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class UrlHelper
{
    /**
     * Generate the URL which will be used to point to the online version.
     *
     * @param string $onlineReference
     * @param Carbon $onlineExpiry
     * @return string
     */
    public static function generateOnlineVersionUrl(string $onlineReference, Carbon $onlineExpiry): string
    {
        return URL::temporarySignedRoute('mail.view-online', $onlineExpiry, [
            'onlineMailable' => $onlineReference
        ]);
    }
}
