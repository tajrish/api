<?php

namespace Tajrish\Providers;

use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Dingo\Api\Transformer\Factory')->setAdapter(function ($app) {
            return new Fractal(new Manager, 'include', ',');
        });

        $this->app->make('Dingo\Api\Exception\Handler')->register(function (ModelNotFoundException $exception) {
            return Response::make([
                'message' => trans('messages.not_found'),
                'status_code' => 404
            ], 404);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }
}
