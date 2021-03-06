<?php

namespace CognitivySpace\LPSP;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Registers a configuration
     *
     * @param string $path
     * @param string $name
     */
    protected function registerConfig($path, $name)
    {
        $this->publishes([$path => base_path('config/' . $name)]);
    }

    /**
     * Gets the router for the specific application
     *
     * @return \Illuminate\Routing\Router|\Laravel\Lumen\Routing\Router
     */
    protected function router()
    {
        if ($this->isLaravel()) {
            return app('router');
        } else {
            /** @var Application $app */
            $app = $this->app;
            return $app->router;
        }
    }

    /**
     * Determines whether this application is an instance of Lumen
     *
     * @return bool
     */
    protected function isLumen()
    {
        return is_a($this->app, 'Laravel\Lumen\Application');
    }

    /**
     * Determines whether this application is an instance of Laravel
     *
     * @return bool
     */
    protected function isLaravel()
    {
        return is_a($this->app, 'Illuminate\Foundation\Application');
    }

    /**
     * Register Alias function to register an alias based upon
     * whether they are using lumen or laravel
     *
     * @param string $class
     * @param string $name
     */
    protected function registerAlias($class, $name)
    {
        if ($this->isLaravel()) {
            AliasLoader::getInstance()->alias($name, $class);
        } elseif ($this->isLumen()) {
            if (!class_exists($name)) {
                class_alias($class, $name);
            }
        }
    }
}