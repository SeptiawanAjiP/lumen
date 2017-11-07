<?php namespace

App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthMiddleware {
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

    public function handle($request, Closure $next){
        // dd(get_class_methods(app('request')));
        // dd($request);
        try {
            if (! $user = $this->auth->parseToken()->authenticate()) {
                $request->merge(['error' => 'user not found']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $request->merge(['error' => 'kesalahan authentikasi']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $request->merge(['error' => 'kesalahan authentikasi']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $request->merge(['error' => 'kesalahan authentikasi']);
        }

        if(!$request->error){
            $request->merge(['auth_user' => $this->auth->user()]);
        }

        

        return $next($request);
    }
}