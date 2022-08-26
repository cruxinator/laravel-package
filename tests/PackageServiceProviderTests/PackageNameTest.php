<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageNameTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package->name('laravel-package-tools');
    }

    /** @test */
    public function testItWillNotBlowUpWhenANameIsSet()
    {
        $this->assertTrue(true);
    }
}
