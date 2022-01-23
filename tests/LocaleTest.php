<?php

namespace Sammyjo20\Wagonwheel\Tests;

class LocaleTest extends TestCase
{
    public function localeProvider()
    {
        return [
            ['en'],
            ['pt'],
            ['nl'],
        ];
    }

    /**
     * @test
     * @dataProvider localeProvider
     */
    public function it_can_render_translation($locale)
    {
        $translations = require(__DIR__ . '/../resources/lang/' . $locale . '/view-online.php');

        $this->app->setLocale($locale);

        $this->assertTrue($this->app->isLocale($locale));

        $this->assertEquals($translations['message'], __('wagonwheel::view-online.message'));
        $this->assertEquals($translations['link'], __('wagonwheel::view-online.link'));
    }
}
