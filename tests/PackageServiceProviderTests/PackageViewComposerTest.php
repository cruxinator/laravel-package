<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageViewComposerTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasViews()
            ->hasViewComposer('*', function ($view) {
                $view->with('sharedItemTest', 'hello world');
            });
    }

    /** @test */
    public function testItCanLoadTheViewComposerAndRenderSharedData()
    {
        $content = view('package-tools::shared-data')->render();

        $this->assertStringStartsWith('hello world', $content);
    }
}
