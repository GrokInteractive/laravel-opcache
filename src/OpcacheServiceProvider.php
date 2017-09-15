<?php

namespace Appstract\Opcache;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class OpcacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router)
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Clear::class,
                Commands\Config::class,
                Commands\Status::class,
                Commands\Optimize::class,
            ]);
        }

        parent::boot($router);
    }

	public function map(Router $router) {
        $router->group([
            'middleware' => ['\Appstract\Opcache\Http\Middleware\Request'],
            'prefix' => 'opcache-api',
            'namespace'     => 'Appstract\Opcache\Http\Controllers',
        ], function($router) {
            require __DIR__.'/Http/routes.php';
        });
	}
}
