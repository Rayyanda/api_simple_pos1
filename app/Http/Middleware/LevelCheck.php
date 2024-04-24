<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LevelCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$level): Response
    {
        if(in_array($request->user()->level,$level))
        {
         return $next( $request);
        }
 
        return response()->json([
            'status' => false,
            'message'=> "You don't have permission to access this page"
        ],403);
    }
}
