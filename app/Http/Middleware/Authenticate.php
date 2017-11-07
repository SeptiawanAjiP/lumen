<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->auth = $jwt;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if ($this->auth->guard($guard)->guest()) {
        //     return response('Unauthorized.', 401);
        // }

        // return $next($request);
        
        // try {
        //     if (! $user = $this->auth->parseToken()->authenticate()) {
        //         return response()->json(['status' => 'fail',
        //                 'message' => 'user_not_found']);
        //     }
        // } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        //     return response()->json(['status' => 'fail',
        //                 'message' => 'token_invalid']);
        // } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        //     return response()->json(['status' => 'fail',
        //                 'message' => 'token_expired']);
        // } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        //     return response()->json(['status' => 'fail',
        //                 'message' => 'token_absent']);
        // }

        // $request->merge(['auth_user' => $this->auth->user()]);

        // return $next($request);
        
        if($request['error']){
            return response()->json(['status' => 'fail', 'message' => $request->error]);
        }

        return $next($request);
    }
}
