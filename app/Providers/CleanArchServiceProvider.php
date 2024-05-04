<?php

namespace App\Providers;

use App\Logging\Log;
use App\Repositories\Eloquent\CompanyEloquentRepository;
use App\Repositories\Eloquent\PeopleEloquentRepository;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\User\Domain\Repository\CompanyRepositoryInterface;
use Core\User\Domain\Repository\PeopleRepositoryInterface;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class CleanArchServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->bingRepositories();

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
        );

        $this->app->bind(
            LoggerInterface::class,
            Log::class
        );
    }


    private function bingRepositories()
    {
        $this->app->bind(
            PeopleRepositoryInterface::class,
            PeopleEloquentRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class
        );

        $this->app->bind(
            CompanyRepositoryInterface::class,
            CompanyEloquentRepository::class
        );

    }
}