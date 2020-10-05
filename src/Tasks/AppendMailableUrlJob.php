<?php

namespace Sammyjo20\Jockey\Tasks;

use Illuminate\Support\Facades\URL;
use Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException;
use Sammyjo20\Jockey\Interfaces\JobInterface;
use \Swift_Message;
use \DOMDocument;

class AppendMailableUrlJob implements JobInterface
{
    /**
     * @var mixed
     */
    protected $body;

    /**
     * @var string
     */
    protected $onlineReference;

    public function run(): self
    {
        // The compiler dies if there's an & in the HTML. This isn't
        // a great way of dealing with it - but let's give it a go.
        $body = str_replace('&', '&amp;', $this->body);

        $parser = new DOMDocument();
        $parser->loadHTML($body);

        $body = $parser->getElementsByTagName('body');

        if (!$body->count()) {
            throw new ParsingMailableFailedException('The provided mailable\'s content does not contain a <body> tag.');
        }

        // Todo: Clean up, write as blade...
        $component = file_get_contents(__DIR__ . '/../../stubs/components/view-online.html');

//        $linkNode = new DOMDocument();
//        $linkNode->loadHTML($component);

        // Todo: Learn how to DOMDocument.

        foreach($body->item(0)->childNodes as $node)  {
//            $child = $linkNode->getElementsByTagName('body')->item(0)->firstChild;

            $url = URL::temporarySignedRoute('mail.view-online', now()->addDays(30), [
                'onlineMailable' => $this->onlineReference
            ]);

            $link = $parser->createElement('a', 'View Online');
            $link->setAttribute('href', $url);

            $node->insertBefore($link);
        }

        $this->setBody($parser->saveHTML());

        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setOnlineReference(string $reference): self
    {
        $this->onlineReference = $reference;

        return $this;
    }

    /**
     * @param mixed $body
     * @return $this
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }
}
