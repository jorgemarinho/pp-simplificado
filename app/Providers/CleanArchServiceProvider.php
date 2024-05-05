<?php

namespace App\Providers;

use App\Events\TransferMade;
use App\Logging\Log;
use App\Repositories\Eloquent\CompanyEloquentRepository;
use App\Repositories\Eloquent\PeopleEloquentRepository;
use App\Repositories\Eloquent\TransferHistoryEloquentRepository;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\Repositories\Eloquent\WalletEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use App\Services\HttpService;
use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\Services\HttpServiceInterface;
use Core\User\Domain\Repository\CompanyRepositoryInterface;
use Core\User\Domain\Repository\PeopleRepositoryInterface;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Core\Wallet\Domain\Repository\TransferHistoryRepositoryInterface;
use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use Core\Wallet\Interfaces\TransferEventManagerInterface;
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

        $this->app->singleton(
            TransferEventManagerInterface::class,
            TransferMade::class
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

        $this->app->bind(
            WalletRepositoryInterface::class,
            WalletEloquentRepository::class
        );

        $this->app->bind(
            TransferHistoryRepositoryInterface::class,
            TransferHistoryEloquentRepository::class
        );

        $this->app->bind(
            HttpServiceInterface::class,
            HttpService::class
        );
    }
}