<?php

namespace App\Providers;

use App\Models\Tenant\Exam\Exam;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
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
    public function boot()
    {
        if (app()->runningInConsole()) {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
        URL::forceRootUrl(config('app.url'));

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $exams = Exam::with('modules')->get();
                $view->with('exams', $exams);
            }
        });
    }
}
