<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageSharedDataTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasViews()
            ->sharesDataWithAllViews('sharedItemTest', 'hello_world');
    }

    /** @test */
    public function testItCanShareDataWithAllViews()
    {
        $content1 = view('package-tools::shared-data')->render();
        $content2 = view('package-tools::shared-data-2')->render();

        $this->assertStringStartsWith('hello_world', $content1);
        $this->assertStringStartsWith('hello_world', $content2);
    }
}
