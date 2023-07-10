<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAdmin {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle( Request $request, Closure $next ): Response {
        if ( ! auth()->check() || ! auth()->user()->is_admin ) {
            return response( [ 'message' => 'U heeft geen toegang' ], 403 );
        }
        return $next( $request );

    }
}
