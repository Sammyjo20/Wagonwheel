<?php

namespace Sammyjo20\Jockey\Concerns;

use Illuminate\Support\Str;
use Sammyjo20\Jockey\Exceptions\InvalidMailableException;
use \Swift_Message;

trait HasListenerValidation
{
    /**
     * @param $eventMessage
     * @param $eventData
     * @return bool
     * @throws InvalidMailableException
     */
    protected function validOnlineMailableEvent($eventMessage, $eventData): bool
    {
        if (!$this->shouldCreateOnlineVersion($eventData)) {
            return false;
        }

        if (!$this->usingSwiftMailer($eventMessage)) {
            throw new InvalidMailableException(
                'The mailable is not using the SwiftMailer driver. Please use the SwiftMailer mail driver to 
                support online versions.'
            );
        }

        if (!$this->isHtmlContent($eventMessage->getBodyContentType())) {
            throw new InvalidMailableException(
                'The mailable provided is not a HTML email. Please make sure to use text/html as the content 
                type.'
            );
        }

        return true;
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
        return isset($data['onlineReference'], $data['onlineExpiry'])
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
