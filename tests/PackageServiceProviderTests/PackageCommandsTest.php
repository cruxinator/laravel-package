<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestClasses\FourthTestCommand;
use Cruxinator\Package\Tests\TestClasses\OtherTestCommand;
use Cruxinator\Package\Tests\TestClasses\TestCommand;
use Cruxinator\Package\Tests\TestClasses\ThirdTestCommand;

class PackageCommandsTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasCommand(TestCommand::class)
            ->hasCommands([OtherTestCommand::class])
            ->hasCommands(ThirdTestCommand::class, FourthTestCommand::class);
    }

    /** @test */
    public function testItCanExecuteARegisteredCommands()
    {
        $this
            ->artisan('test-command')
            ->assertExitCode(0);

        $this
            ->artisan('other-test-command')
            ->assertExitCode(0);
    }
}
