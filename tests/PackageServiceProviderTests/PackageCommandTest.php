<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestClasses\TestCommand;

class PackageCommandTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasCommand(TestCommand::class);
    }

    /** @test */
    public function testItCanExecuteARegisteredCommands()
    {
        $this
            ->artisan('test-command')
            ->assertExitCode(0);
    }
}
