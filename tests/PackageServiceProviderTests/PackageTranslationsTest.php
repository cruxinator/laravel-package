<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;

class PackageTranslationsTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        $package
            ->name('laravel-package-tools')
            ->hasTranslations();
    }

    /** @test */
    public function testItCanLoadTheTranslations()
    {
        $this->assertEquals('translation', trans('package-tools::translations.translatable'));
    }

    /** @test */
    public function testItCanPublishTheTranslations()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-translations')
            ->assertExitCode(0);

        $path = (function_exists('lang_path'))
            ? lang_path("vendor/package-tools/en/translations.php")
            : resource_path("lang/vendor/package-tools/en/translations.php");

        $this->assertFileExists($path);
    }
}
