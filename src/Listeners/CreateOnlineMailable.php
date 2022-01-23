<?php

namespace Sammyjo20\Wagonwheel\Listeners;

use Illuminate\Support\Str;
use Illuminate\Mail\Events\MessageSending;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;
use Sammyjo20\Wagonwheel\Concerns\HasListenerValidation;

class CreateOnlineMailable
{
    use HasListenerValidation;

    public function handle(MessageSending $event): void
    {
        if (! $this->validOnlineMailableEvent($event->message, $event->data)) {
            return;
        }

        $event->data['onlineViewingReference'] = $this->generateOnlineViewingReference();
        $event->data['onlineViewingExpiry'] = OnlineMailable::getExpirationDate();

        $body = $event->message->getBody();

        $onlineMailable = new OnlineMailable();
        $onlineMailable->uuid = $event->data['onlineViewingReference'];
        $onlineMailable->expires_at = $event->data['onlineViewingExpiry'];
        $onlineMailable->content = $body;
        $onlineMailable->save();
    }

    private function generateOnlineViewingReference(): string
    {
        return Str::uuid()->toString();
    }
}
