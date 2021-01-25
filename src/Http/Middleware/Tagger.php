<?php

namespace GeniusSystems\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Tagger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $tag = md5($response);

        $response->headers->set("GS-Tagged-AT", $response->headers->get("date"));

        // create tag
        Cache::put("resources:etag:" . $tag, $request->getUri(), 604800); // valid for 7 days

        // reference tag for flushing
        Cache::put("resources:etag:uri:" . $request->getUri(), $tag, 604800);

        return $response->withHeaders([
            'ETag'  => $tag,
        ]);
    }
}
