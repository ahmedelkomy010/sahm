<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'lab-licenses',
        'lab-licenses/*',
        'admin/work-orders/*/civil-works/images',
        'admin/work-orders/*/civil-works/attachments',
        'work-orders/*/civil-works/images',
        'work-orders/*/civil-works/attachments',
        'admin/work-orders/*/civil-works/attachments/*',
        'work-orders/*/civil-works/attachments/*'
    ];
} 