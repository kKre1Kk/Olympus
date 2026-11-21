<?php

namespace App\Providers;

use App\Models\God;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('update-god', function (User $user, God $god) {
            return $user->is_admin || $user->id === $god->user_id;
        });

        Gate::define('delete-god', function (User $user, God $god) {
            return $user->is_admin || $user->id === $god->user_id;
        });

        Gate::define('restore-god', function (User $user, God $god) {
            return $user->is_admin;
        });

        Gate::define('force-delete-god', function (User $user, God $god) {
            return $user->is_admin;
        });
    }
}
