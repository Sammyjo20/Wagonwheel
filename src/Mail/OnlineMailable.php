<?php

namespace Sammyjo20\Jockey\Mail;

use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OnlineMailable extends BaseMailable
{
    /**
     * @var string
     */
    public $onlineReference;

    /**
     * @var Carbon
     */
    public $onlineExpiry;

    /**
     * OnlineMailable constructor.
     */
    public function __construct()
    {
        $this->generateOnlineReference()
            ->generateExpiryDate();
    }

    /**
     * @return $this
     */
    private function generateOnlineReference(): self
    {
        $this->onlineReference = Str::uuid()->toString();

        return $this;
    }

    /**
     * @return $this
     */
    private function generateExpiryDate(): self
    {
        // Todo: Add config file to customise expiry.

        $this->onlineExpiry = now()->addDays(30);

        return $this;
    }
}
