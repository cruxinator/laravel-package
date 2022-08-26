<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageViewsWithCustomNamespaceTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasViews('custom-namespace');
    }

    /** @test */
    public function testItCanLoadTheViewsWithACustomNamespace()
    {
        $content = view('custom-namespace::test')->render();

        $this->assertStringStartsWith('This is a blade view', $content);
    }

    /** @test */
    public function testItCanPublishTheViewsWithACustomNamespace()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-views')
            ->assertExitCode(0);

        $this->assertFileExists(base_path('resources/views/vendor/package-tools/test.blade.php'));
    }
}
