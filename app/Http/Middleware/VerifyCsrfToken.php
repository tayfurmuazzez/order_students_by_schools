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
        '/admin/school/create',
        '/admin/school/update',
        '/admin/school/delete',
        '/admin/student/create',
        '/admin/student/update',
        '/admin/student/delete'
    ];
}
