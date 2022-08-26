<?php

namespace Cruxinator\Package\Tests\TestPackage\Src;

use Closure;
use Cruxinator\Package\Package;
use Cruxinator\Package\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public static ?Closure $configurePackageUsing = null;

    public function configurePackage(Package $package): void
    {
        $configClosure = self::$configurePackageUsing ?? function (Package $package) {
        };

        ($configClosure)($package);
    }
}
