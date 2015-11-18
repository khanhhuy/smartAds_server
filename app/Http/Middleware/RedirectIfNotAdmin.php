<?php namespace App\Http\Middleware;

use App\PortalUser;
use Auth;
use Closure;
use Utils;

class RedirectIfNotAdmin
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
        if (PortalUser::getCurrentUserType() !== 'Admin') {
            if (Auth::guest()) {
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect()->guest('auth/login');
                }
            }
            return redirect(url(Utils::getCurrentUserHome()));
        }
        return $next($request);
    }

}
