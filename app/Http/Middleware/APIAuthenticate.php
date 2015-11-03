<?php namespace App\Http\Middleware;

use Closure;
use Route;

class APIAuthenticate
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
        if (!$request->has('access_token')) {
            $grant = false;
        } else {
            $customer = Route::input('customers');
            $token = $request->input('access_token');
            $grant = ($customer->getRememberToken() === $token);

            //TODO for dev purpose
            if ($token === 'dev') {
                $grant = true;
            }
        }

        if (!$grant) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }

}
