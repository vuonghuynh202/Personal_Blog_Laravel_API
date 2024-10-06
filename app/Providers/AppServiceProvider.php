<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('web.*', function ($view) {
            $token = Cookie::get('token');
            
            if ($token) {
                try {
                    $user = JWTAuth::setToken($token)->authenticate();

                    $view->with('user', $user);
                } catch (\Exception $e) {
                    $view->with('user', null);
                }
            } else {
                $view->with('user', null);
            }
        });
    }
}
