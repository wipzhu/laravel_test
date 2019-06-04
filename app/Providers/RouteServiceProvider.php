<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $currentDomain;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->adminNamespace = 'App\Http\Controllers\Admin';
        $this->homeNamespace = 'App\Http\Controllers\Home';
        $this->apiNamespace = 'App\Http\Controllers\Api';
        //        $this->currentDomain = $this->app->request->server->get('HTTP_HOST');
        $this->currentDomain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $adminUrl = config('route.admin_url');
        $homeUrl = config('route.home_url');
        $apiUrl = config('route.api_url');

        switch ($this->currentDomain) {
            case $apiUrl:
                // API路由
                Route::middleware('api')
                    ->domain($apiUrl)
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
                break;
            case $adminUrl:
                // 后端路由
                Route::middleware('web')
                    ->domain($adminUrl)
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web_admin.php'));
                break;  
            default:  
                // 前端路由
                Route::middleware('web')
                    ->domain($homeUrl)
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web_home.php'));
                break;
        }  
//        $this->mapApiRoutes();
//        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
//        Route::prefix('api')
//             ->middleware('api')
        Route::middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
