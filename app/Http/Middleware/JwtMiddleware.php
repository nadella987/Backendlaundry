<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try
        {
            $user = JWTAuth::parseToken()->authenticate();
        }
        catch (Exception $e)
        {
            if($e instanceof \Tymon\JWTAuth\Exception\TokenInvalidException)
            {
                return response()->json(['message' => 'Token Invalid']);
            }
            else if($e instanceof \Tymon\JWTAuth\Exception\TokenExpiredException)
            {
            return response()->json(['message' => 'Token Expired']);
            }
            else 
            {
             return response()->json(['message' => 'Authorization token not found']);
            }   
        }
        if ($user && in_array($user->role, $roles)) {
        return $next($request);
    }

    return response()->json(['message' => 'You are unauthorized']);

    }

    
}
