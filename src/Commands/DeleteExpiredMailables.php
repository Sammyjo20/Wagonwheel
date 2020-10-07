<?php

namespace Sammyjo20\Wagonwheel\Commands;

use Illuminate\Console\Command;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;

class DeleteExpiredMailables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wagonwheel:delete-expired-mailables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete any mailables that have passed their expiry date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        OnlineMailable::whereDate('expires_at', '<=', now())
            ->delete();
    }
}
