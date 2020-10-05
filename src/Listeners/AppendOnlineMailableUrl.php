<?php

namespace Sammyjo20\Jockey\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Sammyjo20\Jockey\Exceptions\InvalidMailableException;
use Sammyjo20\Jockey\Tasks\AppendMailableUrlJob;
use \Swift_Message;

class AppendOnlineMailableUrl
{
    /**
     * @param MessageSending $event
     * @throws InvalidMailableException
     * @throws \Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException
     */
    public function handle(MessageSending $event): void
    {
        if (!$this->shouldCreateOnlineVersion($event->data)) {
            return;
        }

        if (!$this->usingSwiftMailer($event->message)) {
            throw new InvalidMailableException(
                'The mailable is not using the SwiftMailer driver. Please use the SwiftMailer mail driver to 
                support online versions.'
            );
        }

        if (!$this->isHtmlContent($event->message->getBodyContentType())) {
            throw new InvalidMailableException(
                'The mailable provided is not a HTML email. Please make sure to use text/html as the content 
                type.'
            );
        }

        $this->appendMailableUrlToBody(
            $event->message, $event->data['onlineReference']
        );
    }

    /**
     * @param Swift_Message $message
     * @param string $onlineReference
     * @throws \Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException
     */
    private function appendMailableUrlToBody(Swift_Message &$message, string $onlineReference)
    {
        $contentType = Str::lower($message->getBodyContentType());

        $body = (new AppendMailableUrlJob)
            ->setOnlineReference($onlineReference)
            ->setBody($message->getBody())
            ->run()
            ->getBody();

        $message->setBody($body, $contentType);
    }

    /**
     * @param string $contentType
     * @return bool
     */
    private function isHtmlContent(string $contentType): bool
    {
        return Str::lower($contentType) === 'text/html';
    }

    /**
     * @param array $data
     * @return bool
     */
    private function shouldCreateOnlineVersion(array $data): bool
    {
        return isset($data['onlineReference']) && isset($data['onlineExpiry'])
            && !is_null($data['onlineReference']) && !is_null($data['onlineExpiry']);
    }

    /**
     * @param $message
     * @return bool
     */
    private function usingSwiftMailer($message): bool
    {
        return $message instanceof Swift_Message;
    }
}
