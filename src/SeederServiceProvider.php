<?php

namespace Seeder;

use Illuminate\Support\ServiceProvider;
use Seeder\Console\Commands\SeederMakeCommand;
use Seeder\Console\Commands\SeedersHandlerCommand;
use Seeder\Console\Commands\UpdatePreviousSeedersCommand;

class SeederServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->commands([
            SeedersHandlerCommand::class,
            UpdatePreviousSeedersCommand::class
        ]);

        $this->app->extend('command.seeder.make', function () {
            return new SeederMakeCommand($this->app['files']);
        });
    }
}