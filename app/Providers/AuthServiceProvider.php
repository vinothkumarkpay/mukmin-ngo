<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Welfare\AdminAccessService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        $access = app(AdminAccessService::class);

        foreach ($access->allPermissionSlugs() as $slug) {
            Gate::define($slug, function (User $user) use ($slug) {
                return $user->hasPermission($slug);
            });
        }
    }
}
