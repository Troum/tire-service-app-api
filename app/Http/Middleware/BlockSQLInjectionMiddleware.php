<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BlockSQLInjectionMiddleware
{
    public $except = [
      'order'
    ];
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $disAllowed = [
            'SELECT',
            'FROM',
            'UPDATE',
            'SET',
            'WHERE',
            'DELETE',
            'INSERT',
            'INTO',
            'CREATE',
            'ALTER',
            'DROP',
            'INDEX',
            'WLRM',
            'PG_SLEEP',
            '(',
            ')',
            '+',
            '__',
            '*',
            '**',
            '***',
            ';',
            '"',
            "'"
        ];
        collect($disAllowed)->each(function ($item) use ($request) {
            if (Str::contains(Str::lower($request->fullUrl()), Str::lower($item))) {
                abort(403);
            }
        });
        return $next($request);
    }
}
