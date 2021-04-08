<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    protected $namespace = 'App\Http\Controllers';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
       
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapApiRoutes()
    {   
        
        Route::group([
            'middleware' => ['api', 'cors'],
            'namespace' => $this->namespace,
            'prefix' => 'auth'
        
        ], function ($router) {
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/user-profile', [AuthController::class, 'userProfile']);    
        });

        Route::group([
            'middleware' => ['api', 'cors'],
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
             //Add you routes here, for example:
            //Route::apiResource('/posts','PostController');
            Route::get('employees', 'ApiController@getAllEmployees');
            Route::get('employees/{id}', 'ApiController@getEmployee');
            Route::post('employees', 'ApiController@createEmployee');//-> middleware('cors');
            Route::put('employees/{id}', 'ApiController@updateEmployee');//-> middleware('cors');
            Route::delete('employees/{id}','ApiController@deleteEmployee');
            Route::post('uploadfile', 'ApiController@uploadFile');
            Route::post('multiple-image-upload', 'ApiController@store');
        });

        Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
            Route::group(['prefix' => 'role'], function () {
                Route::get('/','RoleController@index');
                Route::post('/store','RoleController@store');
                Route::get('/{id}/edit','RoleController@edit');
                Route::put('/{id}/update','RoleController@update');
                Route::put('/{id}/status','RoleController@changeStatus');
                Route::delete('/{id}/delete','RoleController@delete');
             });
        });

        Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
            Route::group(['prefix' => 'category'], function () {
                Route::get('/','CategoryController@index');
                Route::get('/create','CategoryController@create');
                Route::post('/store','CategoryController@store');
                Route::get('/{id}/edit','CategoryController@edit');
                Route::post('/{id}/update','CategoryController@update');
                Route::put('/{id}/status','CategoryController@changeStatus');
                Route::delete('/{id}/delete','CategoryController@delete');
             });
        });

        Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
            Route::group(['prefix' => 'product'], function () {
                Route::get('/','ProductController@index');
                Route::get('/create','ProductController@create');
                Route::post('/store','ProductController@store');
                Route::get('/{id}/edit','ProductController@edit');
                Route::post('/{id}/update','ProductController@update');
                Route::put('/{id}/status','ProductController@changeStatus');
                Route::delete('/{id}/delete','ProductController@delete');
             });
        });
    }
}
