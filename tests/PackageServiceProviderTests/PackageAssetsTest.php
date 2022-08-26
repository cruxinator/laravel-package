<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageAssetsTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasAssets();
    }

    /** @test */
    public function testItCanPublishTheAssets()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-assets')
            ->assertExitCode(0);

        $this->assertFileExists(public_path('vendor/package-tools/dummy.js'));
    }
}
