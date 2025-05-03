<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // تعديل Gate لاستخدام حالة is_admin للتحقق من صلاحيات المشرف
        Gate::define('admin', function ($user) {
            // التحقق من الصلاحية بالشكل الصحيح مع أي نوع من البيانات
            return $user->is_admin == 1 || $user->is_admin === true || $user->is_admin === 1;
        });
    }
} 