<?php

namespace Seeder\Tests;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Seeder\SeederServiceProvider;

class UpdatePreviousSeedersCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->useDatabasePath(storage_path('framework/testing/database/'));
    }

    protected function getPackageProviders($app)
    {
        return [SeederServiceProvider::class];
    }

    /**
     * @test
     */
    function it_will_migrate_previous_seeders_to_new_format()
    {
        $this->app->extend('command.seeder.make', function () {
            return new SeederMakeCommand($this->app['files']);
        });

        $this->artisan('make:seeder FooSeeder');

        $this->assertFileExists(database_path('seeders/FooSeeder.php'));

        $this->artisan('seed:update');

        $this->assertFileExists(database_path('seeders/' . date('Y_m_d_His_') . "foo_seeder.php"));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('framework/testing/database'));

        parent::tearDown();
    }
}