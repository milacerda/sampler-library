<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $token = $this->authenticate($request);

        $response = $next($request);

        return $this->setAuthenticationHeader($response, $token);
    }

    private function checkToken(Request $request)
    {
        if (!$this->auth->parser()->setRequest($request)->hasToken() || !$this->auth->parseToken()->authenticate()) {
            throw new UnauthorizedException;
        }
    }

    private function authenticate(Request $request)
    {
        try {
            $this->checkToken($request);
        } catch (TokenExpiredException $e) {

            try {
                $token = $this->auth->refresh();
                $request = $this->setAuthenticationHeader($request, $token);

                $this->checkToken($request);

                return $token;
            } catch (Throwable $th) {
                throw new UnauthorizedException;
            }
        } catch (Throwable $th) {
            throw new UnauthorizedException;
        }
    }

    private function setAuthenticationHeader($object, string $token = null)
    {
        if ($token) {
            $object->headers->set('Authorization', "Bearer {$token}");
        }

        return $object;
    }
}
