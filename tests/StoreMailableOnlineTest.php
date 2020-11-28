<?php

namespace Sammyjo20\Wagonwheel\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;
use Sammyjo20\Wagonwheel\Tests\Mail\ExampleMail;

class StoreMailableOnlineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['mail.driver' => 'log']);
    }

    /** @test */
    function a_user_can_store_a_mailable_online_with_an_expiration_date()
    {
        Config::set('wagonwheel.message_expires_in_days', 1);

        Mail::to('jbraunnl@gmail.com')->send(new ExampleMail());

        $this->assertCount(1, OnlineMailable::all());

        $mailable = OnlineMailable::first();

        $this->assertTrue(Carbon::parse($mailable->expires_at)->isTomorrow());

        $url = $mailable->getSignedUrl();

        $this->get($url)->assertOk();

        $this->travel(25)->hours();

        $this->get($url)->assertNotFound();
    }

    /** @test */
    function a_user_can_store_a_mailable_online_without_an_expiration_date()
    {
        Config::set('wagonwheel.message_expires_in_days', 0);

        Mail::to('jbraunnl@gmail.com')->send(new ExampleMail());

        $this->assertCount(1, OnlineMailable::all());

        $mailable = OnlineMailable::first();

        $url = $mailable->getSignedUrl();

        $this->get($url)->assertOk();

        $this->travel(25)->years();

        $this->get($url)->assertOk();
    }
}
