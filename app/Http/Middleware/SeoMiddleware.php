<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Support\Facades\Route;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $crawlers = [
            'facebookexternalhit/1.1',
            'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
            'Facebot',
            'Twitterbot',
        ];

        $userAgent = $request->header('User-Agent');

        if (in_array($userAgent, $crawlers)) {
            // switch (Route::getCurrentRoute()->getPath()) {
                // case "tournaments/{tournament}/register":
                    // $tournament = Tournament::where('slug', $request->tournament)->first();
                    $url = $request->url();
                    return response()->view('static', compact('url'));
            // }
        }
        return $next($request);
    }
}