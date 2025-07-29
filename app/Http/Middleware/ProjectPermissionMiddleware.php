<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $projectType): Response
    {
        // التحقق من وجود المستخدم المسجل
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // التحقق من صلاحية الوصول لنوع المشروع
        if (!$user->canAccessProjectType($projectType)) {
            // إذا لم يكن لديه أي صلاحية مشروع، وجهه لصفحة خطأ
            if (!$user->hasAnyProjectAccess()) {
                abort(403, 'ليس لديك صلاحية للوصول إلى أي من المشاريع. يرجى التواصل مع مشرف النظام.');
            }
            
            // إذا كان لديه صلاحية لمشاريع أخرى، وجهه للوحة التحكم
            return redirect()->route('admin.dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذا النوع من المشاريع.');
        }

        return $next($request);
    }
}
