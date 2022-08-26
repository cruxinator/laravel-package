<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestClasses\TestCommand;

class PackageCommandWithinAppTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasRoutes('web')
            ->hasCommand(TestCommand::class);
    }

    /** @test */
    public function testItCanExecuteARegisteredCommandInTheContextOfTheApp()
    {
        $response = $this->get('execute-command');

        $response->assertSee('output of test command');
    }
}
