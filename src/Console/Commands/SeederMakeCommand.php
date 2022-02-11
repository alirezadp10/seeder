<?php

namespace Seeder\Console\Commands;

use Illuminate\Support\Str;

/**
 * @see \Seeder\Tests\SeederMakeCommandTest
 */
class SeederMakeCommand extends \Illuminate\Database\Console\Seeds\SeederMakeCommand
{
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        $file = basename($path);

        $this->line("<info>Created Seeder:</info> {$file}");

        parent::handle();
    }

    protected function getPath($name)
    {
        if (is_dir($this->laravel->databasePath().'/seeds')) {
            return $this->laravel->databasePath().'/seeds/'.$this->getDatePrefix().'_'.Str::snake($name).'.php';
        } else {
            return $this->laravel->databasePath().'/seeders/'.$this->getDatePrefix().'_'.Str::snake($name).'.php';
        }
    }

    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    protected function qualifyClass($name)
    {
        return Str::studly($name);
    }
}
