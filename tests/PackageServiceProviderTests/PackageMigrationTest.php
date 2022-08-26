<?php

namespace Cruxinator\Package\Tests\PackageServiceProviderTests;

use Cruxinator\Package\Package;
use Cruxinator\Package\Tests\TestTime;

class PackageMigrationTest extends PackageServiceProviderTestCase
{
    public function configurePackage(Package $package)
    {
        TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');

        $package
            ->name('laravel-package-tools')
            ->hasMigration('create_another_laravel_package_tools_table')
            ->hasMigration('create_regular_laravel_package_tools_table')
            ->runsMigrations();
    }

    /** @test */
    public function testItCanPublishTheMigration()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-migrations')
            ->assertExitCode(0);

        $this->assertFileExists(database_path('migrations/2020_01_01_000001_create_another_laravel_package_tools_table.php'));
    }

    /** @test */
    public function testItCanPublishTheMigrationWithoutBeingStubbed()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-migrations')
            ->assertExitCode(0);

        $this->assertFileExists(database_path('migrations/2020_01_01_000002_create_regular_laravel_package_tools_table.php'));
    }

    /** @test */
    public function testItDoesNotOverwriteTheExistingMigration()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-migrations')
            ->assertExitCode(0);

        $filePath = database_path('migrations/2020_01_01_000001_create_another_laravel_package_tools_table.php');

        $this->assertFileExists($filePath);

        file_put_contents($filePath, 'modified');

        $this
            ->artisan('vendor:publish --tag=package-tools-migrations')
            ->assertExitCode(0);

        $this->assertStringEqualsFile($filePath, 'modified');
    }

    /** @test */
    public function testItDoesOverwriteTheExistingMigrationWithForce()
    {
        $this
            ->artisan('vendor:publish --tag=package-tools-migrations')
            ->assertExitCode(0);

        $filePath = database_path('migrations/2020_01_01_000001_create_another_laravel_package_tools_table.php');

        $this->assertFileExists($filePath);

        file_put_contents($filePath, 'modified');

        $this
            ->artisan('vendor:publish --tag=package-tools-migrations  --force')
            ->assertExitCode(0);

        $this->assertStringEqualsFile(
            $filePath,
            file_get_contents(__DIR__.'/../TestPackage/database/migrations/create_another_laravel_package_tools_table.php.stub')
        );
    }

    /** @test * */
    public function testItCanRunMigrationsWhichRegistersThem()
    {
        /** @var \Illuminate\Database\Migrations\Migrator $migrator */
        $migrator = app('migrator');

        $this->assertCount(2, $migrator->paths());
        $this->assertStringContainsString('laravel_package_tools', $migrator->paths()[0]);
    }
}
