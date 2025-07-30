<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsNotLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');

        if ($user && $request->isMethod('get')) {
            // Verifica se a chave 'is_admin' existe na sessão
            if (isset($user['is_admin']) && $user['is_admin']) {
                // Se for admin, redireciona para o painel de admin
                return redirect()->route('admin');
            }

            // Se não for admin, redireciona para confirmação de inscrição
            return redirect()->route('confirmarLoginInscricao');
        }

        return $next($request);
    }   
}