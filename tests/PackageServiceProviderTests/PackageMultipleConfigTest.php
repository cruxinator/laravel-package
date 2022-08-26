<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageMultipleConfigTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasConfigFile(['package-tools', 'alternative-config']);
    }

    /** @test */
    public function testItCanRegisterMultipleConfigFiles()
    {
        $this->assertEquals('value', config('package-tools.key'));

        $this->assertEquals('alternative_value', config('alternative-config.alternative_key'));
    }
}
