<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $abilities = collect(config('access.roles', []))
            ->flatten()
            ->unique()
            ->values();

        $abilities->each(function (string $ability): void {
            Gate::define($ability, fn (User $user): bool => $user->hasAbility($ability));
        });
    }
}
