<?php

namespace App\Http\Middleware;

use Closure;

use Throwable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT as JWT;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * @var JWT
     */
    protected $auth;

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

    public function __construct(JWT $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $token = $this->authenticate($request) || null;

        $response = $next($request);

        return $this->setAuthenticationHeader($response, $token);
    }

    public function checkToken(Request $request)
    {
        if (!$this->auth->parser()->setRequest($request)->hasToken() || !$this->auth->parseToken()) {
            throw new UnauthorizedException;
        }
    }

    public function authenticate(Request $request)
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
        } catch (TokenInvalidException $th) {
            // echo $th->getMessage();
            throw new UnauthorizedException;
        }
    }

    public function setAuthenticationHeader($object, string $token = null)
    {
        if ($token) {
            $object->headers->set('Authorization', "Bearer {$token}");
        }

        return $object;
    }
}
