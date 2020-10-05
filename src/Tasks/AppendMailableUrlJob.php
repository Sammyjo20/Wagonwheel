<?php

namespace Sammyjo20\Jockey\Tasks;

use Carbon\Carbon;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use Sammyjo20\Jockey\Exceptions\ParsingMailableFailedException;
use Sammyjo20\Jockey\Helpers\UrlHelper;
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
     * @var Carbon
     */
    protected $onlineExpiry;

    /**
     * @var string
     */
    protected $onlineReference;

    public function run(): self
    {
        $body = $this->appendComponentToBody();

        // Todo: Minify for DB ONLY

        $this->setBody($body);

        return $this;
    }

    /**
     * @return string
     * @throws ParsingMailableFailedException
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    private function appendComponentToBody(): string
    {
        $message = $this->createDomFromHtmlString($this->body);
        $bodies = $message->find('body');

        if (!$bodies->count()) {
            throw new ParsingMailableFailedException('Could not find a <body> tag in the mailable.');
        }

        $component = $this->createDomFromHtmlString($this->getComponentHtml());
        $body = $bodies[0];
        $bodyChildren = $body->getChildren();

        $mode = 'prepend'; // Or append

        if ($mode === 'prepend' && !count($bodyChildren)) {
            throw new ParsingMailableFailedException('There are no children inside the <body> tag.');
        }

        foreach(array_reverse($component->getChildren()) as $child) {
            if ($mode === 'append') {
                $body->addChild($child);
                continue;
            }

            if ($mode === 'prepend') {
                $body->insertBefore($child, $bodyChildren[0]->id());
            }
        }

        return $message->outerHtml;
    }

    /**
     * @param string $string
     * @return Dom
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    private function createDomFromHtmlString(string $string): Dom
    {
        $options = (new Options())
            ->setRemoveStyles(false)
            ->setRemoveScripts(false)
            ->setRemoveSmartyScripts(false)
            ->setRemoveDoubleSpace(false)
            ->setPreserveLineBreaks(true)
            ->setWhitespaceTextNode(true)
            ->setStrict(false);

        return (new Dom)->setOptions($options)->loadStr($string);
    }

    /**
     * @return string
     */
    private function getComponentHtml(): string
    {
        $url = UrlHelper::generateOnlineVersionUrl($this->onlineReference, $this->onlineExpiry);

        return view('jockey::components.view-online', ['url' => $url])->render();
    }

    public function runOld(): self
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
        $component = file_get_contents(__DIR__ . '/../../stubs/components/view-online.blade.php');

//        $linkNode = new DOMDocument();
//        $linkNode->loadHTML($component);

        // Todo: Learn how to DOMDocument.

        foreach($body->item(0)->childNodes as $node)  {
//            $child = $linkNode->getElementsByTagName('body')->item(0)->firstChild;

            $url = URL::temporarySignedRoute('mail.view-online', $this->onlineExpiry, [
                'onlineMailable' => $this->onlineReference
            ]);

            $link = $parser->createElement('a', 'View Online');
            $link->setAttribute('href', $url);

            $node->insertBefore($link);
        }

        dd($parser->saveHTML());

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
     * @param Carbon $expiry
     * @return $this
     */
    public function setOnlineExpiry(Carbon $expiry): self
    {
        $this->onlineExpiry = $expiry;

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
