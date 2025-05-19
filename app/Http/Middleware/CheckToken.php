<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->getToken($request);
        $validated = $this->checkToken($token);

        if ($validated === false || $token === false) {
            return \response()->json(['error' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    private function getToken($request): string|bool
    {
        $str = $request->header('Authorization');
        if ($this->startsWith($str, 'Bearer ')) {
            return substr($str, 7);
        }
        return false;
    }

    private function startsWith($haystack, $needle): bool
    {
        $len = strlen($needle);
        return (substr($haystack, 0, $len) === $needle);
    }


    private function checkToken(string $token): bool
    {
        if (empty($token)) {
            return false;
        }
        $t = base64_encode('test123');
        if ($t != $token) {
            return false;
        }

        return true;
    }
}
