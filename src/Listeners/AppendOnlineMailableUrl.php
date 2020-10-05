<?php

namespace Sammyjo20\Jockey\Listeners;

use Carbon\Carbon;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Sammyjo20\Jockey\Actions\AppendUrlToMailableContent;
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

        if (!isset($event->data['onlineViewingReference'], $event->data['onlineViewingExpiry'])) {
            return;
        }

        $this->appendUrlToMailableContent(
            $event->message, $event->data['onlineViewingReference'], $event->data['onlineViewingExpiry']
        );
    }

    /**
     * @param Swift_Message $message
     * @param string $viewingReference
     * @param Carbon $viewingExpiry
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException
     */
    private function appendUrlToMailableContent(Swift_Message &$message, string $viewingReference, Carbon $viewingExpiry)
    {
        $updatedContent = (new AppendUrlToMailableContent($viewingReference, $viewingExpiry, $message->getBody()))
            ->execute();

        $message->setBody($updatedContent, 'text/html');
    }
}
