<?php

namespace Sammyjo20\Wagonwheel\Tests;

class LocaleTest extends TestCase
{
    /** @test */
    function it_can_render_en_translation()
    {
        $enTranslations = include __DIR__ . '/../resources/lang/en/view-online.php';

        $this->app->setLocale('en');

        $this->assertTrue($this->app->isLocale('en'));

        $this->assertEquals($enTranslations['message'], __('wagonwheel::view-online.message'));

        $this->assertNotNull($enTranslations['link'], __('wagonwheel::view-online.message'));
    }

    /** @test */
    function it_can_render_pt_translation()
    {
        $ptTranslations = include __DIR__ . '/../resources/lang/pt/view-online.php';

        $this->app->setLocale('pt');

        $this->assertTrue($this->app->isLocale('pt'));

        $this->assertEquals($ptTranslations['message'], __('wagonwheel::view-online.message'));

        $this->assertNotNull($ptTranslations['link'], __('wagonwheel::view-online.message'));
    }

    /** @test */
    function it_can_render_nl_translation()
    {
        $nlTranslations = include __DIR__ . '/../resources/lang/nl/view-online.php';

        $this->app->setLocale('nl');

        $this->assertTrue($this->app->isLocale('nl'));

        $this->assertEquals($nlTranslations['message'], __('wagonwheel::view-online.message'));

        $this->assertNotNull($nlTranslations['link'], __('wagonwheel::view-online.message'));
    }
}
