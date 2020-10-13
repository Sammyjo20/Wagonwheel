<?php

namespace Sammyjo20\Wagonwheel\Tests;

use Sammyjo20\Wagonwheel\WagonwheelServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            WagonwheelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../stubs/migrations/create_online_mailables_table.php.stub';

        // run the up() method of that migration class
        (new \CreateOnlineMailablesTable)->up();
    }
}
