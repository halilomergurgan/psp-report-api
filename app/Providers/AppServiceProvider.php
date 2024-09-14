<?php

namespace App\Providers;

use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use App\Repositories\Repository\CustomerRepository;
use App\Repositories\Repository\TransactionRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
