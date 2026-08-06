<?php

namespace App\Providers;

use App\Helpers\Toastr;
use App\Models\RentAgreement;
use App\Observers\RentAgreementObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Toastr::class, function () {
            return new Toastr;
        });
    }

    public function boot(): void
    {
        RentAgreement::observe(RentAgreementObserver::class);

        Gate::define('admin', fn ($user) => $user->isAdmin());
        Gate::define('agent', fn ($user) => $user->isAgent());
        Gate::define('viewSettings', fn ($user) => $user->isAdmin());
        Gate::define('viewCities', fn ($user) => $user->isAdmin());
        Gate::define('viewTeam', fn ($user) => $user->isAdmin());
        Gate::define('manageAll', fn ($user) => $user->isAdmin());
    }
}
