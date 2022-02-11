<?php

namespace Seeder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @see \Seeder\Tests\SeedersHandlerCommandTest
 */
class SeedersHandlerCommand extends Command
{
    const DATETIME_LENGTH = 17;

    protected $signature = 'seed';

    protected $description = 'Seed new seeders';

    private $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        parent::__construct();
    }

    public function handle()
    {
        ini_set('max_execution_time', '-1');

        ini_set('memory_limit', '-1');

        if ($this->resolveSeeders()->diff(DB::table('seeds')->pluck('seed'))->isEmpty()) {
            $this->info('Nothing to seed.');
            return;
        }

        $this->resolveSeeders()->each(function ($class, $file) {
            if (DB::table('seeds')->where('seed', $class)->exists()) {
                return true;
            }
            $this->warn('Seeding: '.$class);
            $this->files->requireOnce($file);
            Artisan::call('db:seed', ['--class' => $this->resolveClassName($class)]);
            DB::table('seeds')->insert(['seed' => $class]);
            $this->info('Seeded: '.$class);
        });
    }

    private function resolveSeeders()
    {
        return collect($this->files->glob($this->path().'*.php'))->filter(function ($file) {
            return $this->hasCorrectFormat($file);
        })->mapWithKeys(function ($file) {
            return [$file => Str::remove('.php', class_basename($file))];
        });
    }

    private function path()
    {
        return $this->laravel->databasePath().'/seeders/';
    }

    private function resolveClassName($seeder)
    {
        return Str::studly(Str::substr($seeder, self::DATETIME_LENGTH + 1));
    }

    private function hasCorrectFormat($file)
    {
        $datetime = Str::substr(class_basename($file), 0, self::DATETIME_LENGTH);

        return (bool) preg_grep('/[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', [$datetime]);
    }
}
