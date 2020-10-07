<?php

namespace Sammyjo20\Wagonwheel\Listeners;

use Carbon\Carbon;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Sammyjo20\Wagonwheel\Concerns\HasListenerValidation;
use Sammyjo20\Wagonwheel\Exceptions\InvalidMailableException;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;

class CreateOnlineMailable
{
    use HasListenerValidation;

    public function handle(MessageSending $event): void
    {
        if (!$this->validOnlineMailableEvent($event->message, $event->data)) {
            return;
        }

        $event->data['onlineViewingReference'] = $this->generateOnlineViewingReference();
        $event->data['onlineViewingExpiry'] = $this->generateOnlineViewingExpiry();

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

    private function generateOnlineViewingExpiry(): Carbon
    {
        $expiry = config('wagonwheel.message_expires_in_days', 30);

        return Carbon::now()->addDays($expiry);
    }
}
