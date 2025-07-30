<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');
    
        if (!isset($user['is_admin']) || !$user['is_admin']) {
            return redirect()->route('confirmarLoginInscricao')->with('erro', 'Você não tem permissão para acessar esta área.');
        }
    
        return $next($request);
    }
}