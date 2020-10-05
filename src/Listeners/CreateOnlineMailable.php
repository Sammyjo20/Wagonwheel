<?php

namespace Sammyjo20\Jockey\Listeners;

use Illuminate\Support\Str;
use Sammyjo20\Jockey\Exceptions\InvalidMailableException;
use Sammyjo20\Jockey\Models\OnlineMailable;
use \Swift_Message;

class CreateOnlineMailable
{
    /**
     * @param $event
     * @throws \Sammyjo20\Jockey\Exceptions\InvalidMailableException
     */
    public function handle($event): void
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

        $body = $event->message->getBody();

        $onlineMailable = new OnlineMailable();
        $onlineMailable->expires_at = $event->data['onlineExpiry'];
        $onlineMailable->uuid = $event->data['onlineReference'];
        $onlineMailable->content = $body;
        $onlineMailable->save();
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
