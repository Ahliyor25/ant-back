<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use Illuminate\Http\Request;

class DetectLanguage
{
    public const HEADER = 'Accept-Language';   

    public function handle(Request $request, Closure $next)
    {
        $code = $request->header(self::HEADER);


        $language = Language::where('code', $code)->first()
                   ?? Language::where('is_default', true)->first();

        app()->instance('langId',  $language->id);
        app()->instance('langCode', $language->code);


        app()->setLocale($language->code);

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        return $response->header('Content-Language', $language->code);
    }
}