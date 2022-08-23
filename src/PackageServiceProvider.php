<?php

namespace Cruxinator\Package;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Cruxinator\Package\Exceptions\InvalidPackage;
use Cruxinator\Package\Package;
use Cruxinator\Package\Strings\MyStr;
use ReflectionClass;
use ReflectionException;

abstract class PackageServiceProvider extends ServiceProvider
{
    /**
     * @var Package
     */
    protected $package;

    abstract public function configurePackage(Package $package): void;

    /**
     * @return $this
     * @throws InvalidPackage
     * @throws ReflectionException
     */
    public function register(): self
    {
        $this->registeringPackage();

        $this->package = new Package();

        $this->package->setBasePath($this->getPackageBaseDir());

        $this->configurePackage($this->package);

        if (empty($this->package->name)) {
            throw InvalidPackage::nameIsRequired();
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $this->mergeConfigFrom($this->package->basePath("/config/{$configFileName}.php"), $configFileName);
        }

        $this->packageRegistered();

        return $this;
    }

    public function boot(): self
    {
        $this->bootingPackage();

        if ($this->app->runningInConsole()) {
            foreach ($this->package->configFileNames as $configFileName) {
                $this->publishes([
                    $this->package->basePath("/config/{$configFileName}.php") => config_path("{$configFileName}.php"),
                ], "{$this->package->shortName()}-config");
            }

            if ($this->package->hasViews) {
                $this->publishes([
                    $this->package->basePath('/resources/views') => base_path("resources/views/vendor/{$this->package->shortName()}"),
                ], "{$this->package->shortName()}-views");
            }

            $now = Carbon::now();
            foreach ($this->package->migrationFileNames as $migrationFileName) {
                if (! static::migrationFileExists($migrationFileName)) {
                    $this->publishes([
                        $this->package->basePath("/database/migrations/{$migrationFileName}.php.stub") => with($migrationFileName, function ($migrationFileName) use ($now) {
                            $migrationPath = 'migrations/';

                            if (MyStr::contains($migrationFileName, '/')) {
                                $migrationPath .= MyStr::asStringable($migrationFileName)->beforeLast('/')->finish('/');
                                $migrationFileName = MyStr::asStringable($migrationFileName)->afterLast('/');
                            }

                            return database_path($migrationPath.$now->addSecond()->format('Y_m_d_His').'_'.MyStr::asStringable($migrationFileName)->snake()->finish('.php'));
                        }),
                    ], "{$this->package->shortName()}-migrations");
                }
            }

            if ($this->package->hasTranslations) {
                $this->publishes([
                    $this->package->basePath('/resources/lang') => resource_path("lang/vendor/{$this->package->shortName()}"),
                ], "{$this->package->shortName()}-translations");
            }

            if ($this->package->hasAssets) {
                $this->publishes([
                    $this->package->basePath('/resources/dist') => public_path("vendor/{$this->package->shortName()}"),
                ], "{$this->package->shortName()}-assets");
            }
        }

        if (! empty($this->package->commands)) {
            $this->commands($this->package->commands);
        }

        if ($this->package->hasTranslations) {
            $this->loadTranslationsFrom(
                $this->package->basePath('/resources/lang/'),
                $this->package->shortName()
            );

            $this->loadJsonTranslationsFrom($this->package->basePath('/resources/lang/'));
            $this->loadJsonTranslationsFrom(resource_path('lang/vendor/'.$this->package->shortName()));
        }

        if ($this->package->hasViews) {
            $this->loadViewsFrom($this->package->basePath('/resources/views'), $this->package->shortName());
        }
        // Apparently i read the wrong manual.
        /*foreach ($this->package->viewComponents as $componentClass => $prefix) {
            $this->loadViewComponentsAs($prefix, [$componentClass]);
        }*/

        if (count($this->package->viewComponents)) {
            $this->publishes([
                $this->package->basePath('/Components') => base_path("app/View/Components/vendor/{$this->package->shortName()}"),
            ], "{$this->package->name}-components");
        }

        foreach ($this->package->routeFileNames as $routeFileName) {
            $path = "{$this->package->basePath('/routes/')}{$routeFileName}.php";
            $this->loadRoutesFrom($path);
        }

        foreach ($this->package->sharedViewData as $name => $value) {
            View::share($name, $value);
        }

        foreach ($this->package->viewComposers as $viewName => $viewComposer) {
            View::composer($viewName, $viewComposer);
        }

        $this->packageBooted();

        return $this;
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $migrationsPath = 'migrations/';

        $len = strlen($migrationFileName) + 4;

        if (MyStr::contains($migrationFileName, '/')) {
            $migrationsPath .= MyStr::asStringable($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = MyStr::asStringable($migrationFileName)->afterLast('/');
        }

        $globs = glob(database_path("${migrationsPath}*${migrationFileName}.php"));
        foreach ($globs as $filename) {
            if ((substr($filename, -$len) === $migrationFileName.'.php')) {
                return true;
            }
        }

        return false;
    }

    public function registeringPackage()
    {
    }

    public function packageRegistered()
    {
    }

    public function bootingPackage()
    {
    }

    public function packageBooted()
    {
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    protected function getPackageBaseDir(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return dirname($reflector->getFileName());
    }
}
