<?php


namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestCase;
use Cruxinator\Package\Tests\TestPackage\Src\ServiceProvider;

abstract class PackageServiceProviderTestCase extends TestCase
{
    public function setUp(): void
    {
        ServiceProvider::$configurePackageUsing = function (Package $package) {
            $this->configurePackage($package);
        };

        parent::setUp();
    }

    abstract public function configurePackage(Package $package);

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
