<?php

namespace Amethyst\Providers;

use Amethyst\Core\Support\Router;
use Amethyst\Core\Providers\CommonServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class FileGeneratorServiceProvider extends CommonServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();
        $this->loadExtraRoutes();

        $this->app->register(\Amethyst\Providers\DataBuilderServiceProvider::class);
        $this->app->register(\Amethyst\Providers\TemplateServiceProvider::class);
        $this->app->register(\Amethyst\Providers\FileServiceProvider::class);
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

                $router->post('/render', ['as' => 'render', 'uses' => $controller.'@render']);
                $router->post('/{id}/execute', ['as' => 'execute', 'uses' => $controller.'@execute'])->where(['id' => '[0-9]+']);
            });
        }
    }
}
