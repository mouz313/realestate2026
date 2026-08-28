<?php

namespace App\Providers;

use App\Helpers\Toastr;
use App\Models\Company;
use App\Observers\CompanyObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::useBootstrapFive();

        Company::observe(CompanyObserver::class);

        // Delegate any ability that is a real permission slug to the model so
        // Blade @can('any_permission_slug') works for every (incl. admin-created)
        // permission. Return null when denied so the explicitly-defined gates
        // below (admin, owner, view_team, manage_*, ...) still take effect.
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasPermission')) {
                return $user->hasPermission($ability) ?: null;
            }

            return null;
        });

        Gate::define('owner', fn ($user) => $user->isOwner());
        Gate::define('admin', fn ($user) => $user->isAdmin());

        Gate::define('manage_company', fn ($user) => $user->hasPermission('edit_settings'));
        Gate::define('view_team', fn ($user) => $user->hasPermission('view_agents') || $user->hasPermission('view_staff'));
        Gate::define('manage_agents', fn ($user) => $user->hasPermission('manage_agents'));
        Gate::define('manage_staff', fn ($user) => $user->hasPermission('manage_staff'));
        Gate::define('manage_users', fn ($user) => $user->hasPermission('assign_user_roles'));
        Gate::define('manage_roles', fn ($user) => $user->hasPermission('manage_roles'));
        Gate::define('manage_permissions', fn ($user) => $user->hasPermission('manage_permissions'));
        Gate::define('manage_settings', fn ($user) => $user->hasPermission('edit_settings'));
        Gate::define('manage_cities', fn ($user) => $user->hasPermission('manage_cities'));
        Gate::define('view_activity_log', fn ($user) => $user->hasPermission('view_activity_log'));
        Gate::define('view_all_commissions', fn ($user) => $user->hasPermission('view_all_commissions'));
        Gate::define('manage_payouts', fn ($user) => $user->hasPermission('approve_payouts'));
        Gate::define('view_reports', fn ($user) => $user->hasPermission('view_reports'));
        Gate::define('export_reports', fn ($user) => $user->hasPermission('export_reports'));

        Blade::directive('statusBadge', function ($expression) {
            return "<?php echo \\App\\Helpers\\Status::badge($expression); ?>";
        });

        Blade::directive('money', function ($expression) {
            return "<?php echo \\App\\Helpers\\Money::format($expression); ?>";
        });
    }
}
