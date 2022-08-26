<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageViewsTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasViews();
    }

    /** @test */
    public function testItCanLoadTheViews()
    {
        $content = view('package-tools::test')->render();

        $this->assertStringStartsWith('This is a blade view', $content);
    }

    /** @test */
    public function testItCanPublishTheViews()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-views')
            ->assertExitCode(0);

        $this->assertFileExists(base_path('resources/views/vendor/package-tools/test.blade.php'));
    }
}
