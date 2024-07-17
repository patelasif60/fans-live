<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * This namespace is applied to your API controller routes.
     *
     * @var string
     */
    protected $apiNamespace = 'App\Http\Controllers\Api';

    /**
     * This namespace is applied to your Admin controller routes.
     *
     * @var string
     */
    protected $backendNamespace = 'App\Http\Controllers\Backend';

    /**
     * This namespace is applied to your Frontend controller routes.
     *
     * @var string
     */
    protected $frontendNamespace = 'App\Http\Controllers\Frontend';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapAuthRoutes();

        $this->mapFrontendRoutes();
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
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->apiNamespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
             ->middleware('web')
             ->namespace($this->backendNamespace)
             ->group(base_path('routes/backend.php'));
    }

    /**
     * Define the "auth" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/auth.php'));
    }

    /**
     * Define the "frontend" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapFrontendRoutes()
    {
        Route::middleware('web')
             ->namespace($this->frontendNamespace)
             ->group(base_path('routes/frontend.php'));
    }
}
