<?php

namespace Railken\Amethyst\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Api\Support\Router;
use Railken\Amethyst\Common\CommonServiceProvider;

class FileGeneratorServiceProvider extends CommonServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();
        $this->loadExtraRoutes();

        $this->app->register(\Railken\Amethyst\Providers\DataBuilderServiceProvider::class);
        $this->app->register(\Railken\Amethyst\Providers\TemplateServiceProvider::class);
        $this->app->register(\Railken\Amethyst\Providers\FileServiceProvider::class);
        $this->app->register(\Railken\Template\TemplateServiceProvider::class);
    }

    /**
     * Load extras routes.
     */
    public function loadExtraRoutes()
    {
        $config = Config::get('amethyst.file-generator.http.admin.file-generator');

        if (Arr::get($config, 'enabled')) {
            Router::group('admin', Arr::get($config, 'router'), function ($router) use ($config) {
                $controller = Arr::get($config, 'controller');

                $router->post('/render', ['uses' => $controller.'@render']);
                $router->post('/{id}/generate', ['uses' => $controller.'@generate'])->where(['id' => '[0-9]+']);
            });
        }
    }
}
