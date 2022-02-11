<?php

namespace Seeder\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Seeder\SeederServiceProvider;

class SeederMakeCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->useDatabasePath(storage_path('framework/testing/database'));
    }

    protected function getPackageProviders($app)
    {
        return [SeederServiceProvider::class];
    }

    /**
     * @test
     */
    function make_seeder_must_create_files_with_date_time_format()
    {
        $date = date('Y_m_d_His_');

        Artisan::call('make:seeder FooBarSeeder');

        $this->assertFileExists(database_path('seeders/'  . $date . "foo_bar_seeder.php"));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('framework/testing/database'));

        parent::tearDown();
    }
}