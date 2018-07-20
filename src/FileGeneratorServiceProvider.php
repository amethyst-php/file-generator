<?php

namespace Railken\LaraOre;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Railken\LaraOre\Api\Support\Router;

class FileGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/ore.file-generator.php' => config_path('ore.file-generator.php')], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutes();

        config(['ore.permission.managers' => array_merge(Config::get('ore.permission.managers', []), [
            // \Railken\LaraOre\FileGenerator\FileGeneratorManager::class,
        ])]);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->register(\Railken\Laravel\Manager\ManagerServiceProvider::class);
        $this->app->register(\Railken\LaraOre\TemplateServiceProvider::class);
        $this->app->register(\Railken\LaraOre\ApiServiceProvider::class);
        $this->app->register(\Railken\LaraOre\FileServiceProvider::class);
        $this->app->register(\Railken\LaraOre\RepositoryServiceProvider::class);
        $this->mergeConfigFrom(__DIR__.'/../config/ore.file-generator.php', 'ore.file-generator');
    }

    /**
     * Load routes.
     */
    public function loadRoutes()
    {
        if (Config::get('ore.file-generator.http.admin.enabled')) {
            Router::group(Config::get('ore.file-generator.http.admin.router'), function ($router) {
                $controller = Config::get('ore.file-generator.http.admin.controller');

                $router->get('/', ['uses' => $controller.'@index']);
                $router->post('/', ['uses' => $controller.'@create']);
                $router->put('/{id}', ['uses' => $controller.'@update']);
                $router->delete('/{id}', ['uses' => $controller.'@remove']);
                $router->get('/{id}', ['uses' => $controller.'@show']);
                $router->post('/{id}/generate', ['uses' => $controller.'@generate']);
            });
        }
    }
}
