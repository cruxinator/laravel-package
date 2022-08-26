<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageConfigTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasConfigFile();
    }

    /** @test */
    public function testItCanRegisterTheConfigFile()
    {
        $this->assertEquals('value', config('package-tools.key'));
    }

    /** @test */
    public function testItCanPublishTheConfigFile()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-config')
            ->assertExitCode(0);

        $this->assertFileExists(config_path('package-tools.php'));
    }
}
