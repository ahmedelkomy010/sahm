<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // إذا كان المستخدم مشرفاً، السماح له بالوصول مباشرة
        if ($user->is_admin == 1) {
            return $next($request);
        }

        // إذا تم تحديد صلاحية معينة، تحقق منها
        if ($permission && $user->hasPermission($permission)) {
            return $next($request);
        }

        // إذا لم يكن مشرفاً ولا يملك الصلاحية المطلوبة
        return redirect()->route('dashboard')->with('error', 'لا تملك صلاحية الوصول لهذه الصفحة.');
    }
} 