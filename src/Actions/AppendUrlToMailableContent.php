<?php

namespace Sammyjo20\Wagonwheel\Actions;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use Sammyjo20\Wagonwheel\Exceptions\ParsingMailableFailedException;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;

class AppendUrlToMailableContent
{
    /**
     * @var string
     */
    protected $viewingReference;

    /**
     * @var string
     */
    protected $messageContent;

    /**
     * AppendUrlToMailableContent constructor.
     *
     * @param string $viewingReference
     * @param string $messageContent
     */
    public function __construct(string $viewingReference, string $messageContent)
    {
        $this->setViewingReference($viewingReference)->setMessageContent($messageContent);
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
    public function execute(): string
    {
        $placement = config('wagonwheel.component_placement', 'start');
        $message = $this->createDomFromHtmlString($this->messageContent);
        $bodies = $message->find('body');

        if (!$bodies->count()) {
            throw new ParsingMailableFailedException('Could not find a <body> tag in the mailable.');
        }

        $component = $this->createDomFromHtmlString($this->getComponentHtml());
        $componentChildren = array_reverse($component->getChildren());

        $body = $bodies[0];
        $bodyChildren = $body->getChildren();

        if ($placement === 'start' && count($bodyChildren) <= 0) {
            throw new ParsingMailableFailedException('There are no children inside the <body> tag.');
        }

        foreach($componentChildren as $child) {
            if ($placement === 'start') {
                $body->insertBefore($child, $bodyChildren[0]->id());
                continue;
            }

            if ($placement === 'end') {
                $body->addChild($child);
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

    private function getComponentHtml(): string
    {
        /** @var OnlineMailable $mailable */
        $mailable = OnlineMailable::where('uuid', $this->viewingReference)->firstOrFail();

        $url = $mailable->getSignedUrl();

        return view('wagonwheel::components.view-online', ['url' => $url])->render();
    }

    private function setViewingReference(string $value): self
    {
        $this->viewingReference = $value;

        return $this;
    }

    private function setMessageContent(string $value): self
    {
        $this->messageContent = $value;

        return $this;
    }
}
