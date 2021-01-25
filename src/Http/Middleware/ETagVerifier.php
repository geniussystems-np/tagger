<?php

namespace GeniusSystems\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ETagVerifier
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
        $tag = $request->header("If-None-Match");

        if(isset($tag) && Cache::get("resources:etag:" . $tag)) {
            return new Response("", 304);
        }

        return $next($request);
    }
}
