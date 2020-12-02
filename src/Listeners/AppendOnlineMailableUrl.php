<?php

namespace Sammyjo20\Wagonwheel\Listeners;

use \Swift_Message;
use Illuminate\Mail\Events\MessageSending;
use Sammyjo20\Wagonwheel\Actions\AppendUrlToMailableContent;
use Sammyjo20\Wagonwheel\Concerns\HasListenerValidation;
use Sammyjo20\Wagonwheel\Exceptions\InvalidMailableException;

class AppendOnlineMailableUrl
{
    use HasListenerValidation;

    /**
     * @param MessageSending $event
     * @throws InvalidMailableException
     * @throws \Sammyjo20\Wagonwheel\Exceptions\ParsingMailableFailedException
     */
    public function handle(MessageSending $event): void
    {
        if (! $this->validOnlineMailableEvent($event->message, $event->data)) {
            return;
        }

        if (! isset($event->data['onlineViewingReference'])) {
            return;
        }

        $this->appendUrlToMailableContent(
            $event->message,
            $event->data['onlineViewingReference']
        );
    }

    /**
     * @param Swift_Message $message
     * @param string $viewingReference
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \Sammyjo20\Wagonwheel\Exceptions\ParsingMailableFailedException
     */
    private function appendUrlToMailableContent(Swift_Message &$message, string $viewingReference)
    {
        $updatedContent = (new AppendUrlToMailableContent($viewingReference, $message->getBody()))->execute();

        $message->setBody($updatedContent, 'text/html');
    }
}
