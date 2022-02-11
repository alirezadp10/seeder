<?php

namespace Seeder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @see \Seeder\Tests\UpdatePreviousSeedersCommandTest
 */
class UpdatePreviousSeedersCommand extends Command
{
    const DATETIME_LENGTH = 17;

    protected $signature = 'seed:update';

    protected $description = 'update previous seeder file names to datetime format';

    private $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        parent::__construct();
    }

    public function handle()
    {
        collect($this->files->glob($this->path().'*.php'))->reject(function ($file) {
            return $this->hasCorrectFormat($file);
        })->each(function ($file) {
            File::move($file, $this->convertName($file));
        });
    }

    private function path()
    {
        return $this->laravel->databasePath().'/seeders/';
    }

    private function hasCorrectFormat($file)
    {
        $datetime = Str::substr(class_basename($file), 0, self::DATETIME_LENGTH);

        return (bool) preg_grep('/[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}/', [$datetime]);
    }

    private function convertName($file)
    {
        return $this->path().date('Y_m_d_His_').Str::snake(class_basename($file));
    }
}