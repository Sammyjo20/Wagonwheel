<?php

namespace Sammyjo20\Jockey\Listeners;

use Carbon\Carbon;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Sammyjo20\Jockey\Concerns\HasListenerValidation;
use Sammyjo20\Jockey\Exceptions\InvalidMailableException;
use Sammyjo20\Jockey\Models\OnlineMailable;

class CreateOnlineMailable
{
    use HasListenerValidation;

    /**
     * @param MessageSending $event
     * @throws InvalidMailableException
     */
    public function handle(MessageSending $event): void
    {
        if (!$this->validOnlineMailableEvent($event->message, $event->data)) {
            return;
        }

        $body = $event->message->getBody();

        $onlineMailable = new OnlineMailable();
        $onlineMailable->expires_at = $event->data['onlineExpiry'] ?? Carbon::now()->addDays(30);
        $onlineMailable->uuid = $event->data['onlineReference'] ?? Str::uuid()->toString();
        $onlineMailable->content = $body;
        $onlineMailable->save();
    }
}
