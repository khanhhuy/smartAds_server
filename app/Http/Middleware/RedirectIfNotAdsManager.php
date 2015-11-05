<?php namespace App\Http\Middleware;

use App\PortalUser;
use Auth;
use Closure;
use Utils;

class RedirectIfNotAdsManager
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
        //TODO for dev purpose
        return $next($request);

        if (PortalUser::getCurrentUserType() !== 'Ads_Manager') {
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
