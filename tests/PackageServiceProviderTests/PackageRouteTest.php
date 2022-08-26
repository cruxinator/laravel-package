<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestTime;

class PackageRouteTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');

        $package
            ->name('laravel-package-tools')
            ->hasRoutes(['web', 'other']);
    }

    /** @test */
    public function testItCanLoadTheRoute()
    {
        $response = $this->get('my-route');

        $response->assertSeeText('my response');
    }

    /** @test */
    public function testItCanLoadMultipleRoute()
    {
        $adminResponse = $this->get('other-route');

        $adminResponse->assertSeeText('other response');
    }
}
