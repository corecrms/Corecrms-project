<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\TransferMoneyObserver;
use App\Models\TransferMoney;
use App\Repositories\ReserveOrderRepositoryInterface;
use App\Services\ReserveOrderService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\ReserveOrderRepositoryInterface',
            'App\Repositories\ReserveOrderRepository'
        );
        // $this->app->bind(
        //     'App\Services\ReserveOrderServiceInterface',
        //     'App\Services\ReserveOrderService'
        // );
        $this->app->bind(ReserveOrderService::class, function ($app) {
            return new ReserveOrderService($app->make(ReserveOrderRepositoryInterface::class));
        });

        $this->app->bind(
            'App\Repositories\DevicesReturnInterface',
            'App\Repositories\DevicesReturnRepository'
        );

        $this->app->bind(
            'App\Services\DevicesReturnInterface',
            'App\Services\DevicesReturnService'
        );

        $this->app->bind(
            'App\Repositories\SupportInterfaceRepository',
            'App\Repositories\SupportRepository'
        );

        $this->app->bind(
            'App\Services\SupportInterfaceService',
            'App\Services\SupportService'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TransferMoney::observe(TransferMoneyObserver::class);
    }
}
