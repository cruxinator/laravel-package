<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestPackage\Components\TestComponent;

class PackageViewComponentsTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasViews()
            ->hasViewComponent('abc', TestComponent::class);
    }

    /** @test */
    public function testItCanLoadTheViewComponents()
    {
        $this->markTestSkipped("this is from reading wrong version of manual");
        $content = view('package-tools::component-test')->render();

        $this->assertStringStartsWith('<div>hello world</div>', $content);
    }

    /** @test */
    public function testItCanPublishTheViewComponents()
    {
        $this->markTestSkipped("this is from reading wrong version of manual");
        $this
            ->artisan('vendor:publish --tag=laravel-package-tools-components')
            ->assertExitCode(0);
        $this->assertFileExists(base_path('app/View/Components/vendor/package-tools/TestComponent.php'));
    }
}
