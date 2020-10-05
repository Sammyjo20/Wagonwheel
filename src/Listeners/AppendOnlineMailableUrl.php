<?php

namespace Sammyjo20\Jockey\Listeners;

use Carbon\Carbon;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Sammyjo20\Jockey\Concerns\HasListenerValidation;
use Sammyjo20\Jockey\Exceptions\InvalidMailableException;
use Sammyjo20\Jockey\Tasks\AppendMailableUrlJob;
use \Swift_Message;

class AppendOnlineMailableUrl
{
    use HasListenerValidation;

    /**
     * @param MessageSending $event
     * @throws InvalidMailableException
     * @throws \Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException
     */
    public function handle(MessageSending $event): void
    {
        if (!$this->validOnlineMailableEvent($event->message, $event->data)) {
            return;
        }

        // Todo: Add configuration variable which allows people to disable the below logic.

        $this->appendMailableUrlToBody(
            $event->message, $event->data['onlineReference'], $event->data['onlineExpiry']
        );
    }

    /**
     * @param Swift_Message $message
     * @param string $onlineReference
     * @param Carbon $onlineExpiry
     */
    private function appendMailableUrlToBody(Swift_Message &$message, string $onlineReference, Carbon $onlineExpiry)
    {
        $contentType = Str::lower($message->getBodyContentType());

        $body = (new AppendMailableUrlJob)
            ->setOnlineReference($onlineReference)
            ->setOnlineExpiry($onlineExpiry)
            ->setBody($message->getBody())
            ->run()
            ->getBody();

        $message->setBody($body, $contentType);
    }
}
