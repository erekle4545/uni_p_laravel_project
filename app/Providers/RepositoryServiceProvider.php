<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\interfaces\AuthRepositoryInterface;
use App\Repositories\interfaces\PermissionsRepositoryInterface;
use App\Repositories\interfaces\RolesRepositoryInterface;
use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( AuthRepositoryInterface::class,AuthRepository::class);
        $this->app->bind( PermissionsRepositoryInterface::class,PermissionsRepository::class);
        $this->app->bind( RolesRepositoryInterface::class,RolesRepository::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
