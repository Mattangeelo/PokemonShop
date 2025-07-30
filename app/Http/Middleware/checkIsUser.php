<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;


class CheckIsUser
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');
    
        // Se for admin, redireciona pro painel admin
        if ($user && isset($user['is_admin']) && $user['is_admin']) {
            return redirect()->route('admin');
        }
    
        return $next($request);
    }
}