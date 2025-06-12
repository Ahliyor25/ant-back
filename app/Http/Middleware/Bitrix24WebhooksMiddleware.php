<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Bitrix24WebhooksMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next (\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = optional($request->input('auth'))['application_token'];
        $localToken = config('bitrix.webhook.token');

        if(strlen($localToken) === 0 OR $token !== $localToken) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * Логгируем неудачную авторизацию. Или делаем что-то другое, что необходимо.
     * @param array $data
     */
}
