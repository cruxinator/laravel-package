<?php

namespace Cruxinator\Package\Tests\TestClasses;

use Illuminate\Console\Command;

class FourthTestCommand extends Command
{
    public $name = 'fourth-test-command';

    public function handle()
    {
        $this->info('output of test command');
    }
}
