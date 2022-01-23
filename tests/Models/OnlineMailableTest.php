<?php

namespace Sammyjo20\Wagonwheel\Tests\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Sammyjo20\Wagonwheel\Tests\TestCase;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OnlineMailableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_mailable_has_an_expiration_date()
    {
        $expirationDate = now()->addDays(30);

        $mailable = OnlineMailable::factory()->create([
            'expires_at' => $expirationDate,
        ]);

        $this->assertEquals($expirationDate->timestamp, $mailable->expires_at);
    }

    /** @test */
    public function a_mailable_expiration_date_can_be_null()
    {
        $mailable = OnlineMailable::factory()->create([
            'expires_at' => null,
        ]);

        $this->assertNull($mailable->expires_at);
    }

    /** @test */
    public function an_online_mailable_has_the_expiration_date_as_specified_in_the_config()
    {
        Config::set('wagonwheel.message_expires_in_days', 2);

        $expectedExpirationDate = now()->addDays(2);

        $mailable = OnlineMailable::factory()->create();

        $this->assertEquals($expectedExpirationDate->timestamp, $mailable->expires_at);
    }

    /** @test */
    public function an_online_mailable_can_have_an_indefinite_expiration_date()
    {
        Config::set('wagonwheel.message_expires_in_days', 0);

        $mailable = OnlineMailable::factory()->create();

        $this->assertNull($mailable->expires_at);
    }

    /** @test */
    public function an_online_mailable_can_get_its_signed_url()
    {
        /** @var OnlineMailable $mailable */
        $mailable = OnlineMailable::factory()->create();

        $url = URL::temporarySignedRoute('mail.view-online', OnlineMailable::getExpirationDate(), [
            'onlineMailable' => $mailable,
        ]);

        $this->assertEquals($url, $mailable->getSignedUrl());
    }

    /** @test */
    public function an_online_mailable_without_expiration_can_get_its_signed_url()
    {
        /** @var OnlineMailable $mailable */
        $mailable = OnlineMailable::factory()->create([
            'expires_at' => null,
        ]);

        $url = URL::signedRoute('mail.view-online', [
            'onlineMailable' => $mailable,
        ]);

        $this->assertEquals($url, $mailable->getSignedUrl());
    }
}
