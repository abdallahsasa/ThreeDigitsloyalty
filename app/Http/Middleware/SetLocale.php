<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $firstSegment = $request->segment(1);

        if (in_array($firstSegment, ['en', 'ar'], true)) {
            app()->setLocale($firstSegment);
            session(['locale' => $firstSegment]);
        } elseif (in_array($request->query('lang'), ['en', 'ar'], true)) {
            $locale = (string) $request->query('lang');
            app()->setLocale($locale);
            session(['locale' => $locale]);
        } elseif (session()->has('locale') && in_array(session('locale'), ['en', 'ar'], true)) {
            app()->setLocale(session('locale'));
        } else {
            app()->setLocale('ar');
        }

        return $next($request);
    }
}
