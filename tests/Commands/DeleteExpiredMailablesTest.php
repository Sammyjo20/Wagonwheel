<?php

namespace Sammyjo20\Wagonwheel\Tests\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Sammyjo20\Wagonwheel\Commands\DeleteExpiredMailables;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;
use Sammyjo20\Wagonwheel\Tests\TestCase;

class DeleteExpiredMailablesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_deletes_a_mailable_when_it_is_expired()
    {
        // The online mailable will expire after 15 days
        Config::set('wagonwheel.message_expires_in_days', 15);

        OnlineMailable::factory()->create();

        $this->assertCount(1, OnlineMailable::all());

        // After 14 days, the mailable should not be deleted
        $this->travel(14)->days();
        $this->deleteExpiredMailables();
        $this->assertCount(1, OnlineMailable::all());

        // After 15 days, the mailable should be deleted
        $this->travel(1)->days();
        $this->deleteExpiredMailables();
        $this->assertCount(0, OnlineMailable::all());
    }

    /** @test */
    function it_only_deletes_expired_mailables()
    {
        $expiredMailable = OnlineMailable::factory()->expired()->create();
        $nonExpiredMailable = OnlineMailable::factory()->expiresIn(now()->addMonth())->create();

        $this->assertCount(2, OnlineMailable::all());
        $this->assertDatabaseHas('online_mailables', ['uuid' => $expiredMailable->uuid]);
        $this->assertDatabaseHas('online_mailables', ['uuid' => $nonExpiredMailable->uuid]);

        $this->deleteExpiredMailables();

        $this->assertCount(1, OnlineMailable::all());

        $this->assertDatabaseHas('online_mailables', ['uuid' => $nonExpiredMailable->uuid]);
        $this->assertDatabaseMissing('online_mailables', ['uuid' => $expiredMailable->uuid]);
    }

    /** @test */
    function it_does_not_delete_mailables_with_an_expiry_date_of_null()
    {
        OnlineMailable::factory()->create([
            'expires_at' => null,
        ]);

        $this->deleteExpiredMailables();

        $this->assertCount(1, OnlineMailable::all());
    }

    private function deleteExpiredMailables()
    {
        $this->artisan(DeleteExpiredMailables::class)
            ->expectsOutput('Successfully deleted expired online mailables ✅')
            ->assertExitCode(0);
    }
}
