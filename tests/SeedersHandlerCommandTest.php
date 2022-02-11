<?php

namespace Seeder\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Seeder\SeederServiceProvider;

class SeedersHandlerCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->useDatabasePath(storage_path('framework/testing/database/'));
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [SeederServiceProvider::class];
    }

    /**
     * @test
     */
    function it_seed_all_not_seeded_files()
    {
        $this->artisan('migrate:fresh');

        $date = date('Y_m_d_His_');

        $this->artisan('make:seeder FooSeeder');

        $this->assertDatabaseMissing('seeds', ['seed' => $date.'foo_seeder']);

        $this->artisan('seed');

        $this->assertDatabaseHas('seeds', ['seed' => $date.'foo_seeder']);
    }

    /**
     * @test
     */
    function it_does_not_seed_file_which_already_seeded()
    {
        $this->artisan('make:seeder BarSeeder');

        $this->artisan('seed');

        $this->assertDatabaseCount('seeds', 1);

        $this->artisan('seed')->expectsOutput('Nothing to seed.')->assertSuccessful();

        $this->assertDatabaseCount('seeds', 1);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('framework/testing/database'));

        parent::tearDown();
    }
}