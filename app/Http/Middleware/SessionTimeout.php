<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lifetime = config('session.lifetime', 120); // Default 2 hours
        $path = config('session.path', '/');
        $domain = config('session.domain');
        $secure = config('session.secure', true);
        $sameSite = config('session.same_site', 'lax');

        if (Auth::check()) {
            $timeout = $lifetime * 60;
            config(['session.lifetime' => $lifetime]);
            config(['session.expire_on_close' => false]);
            
            $lastActivity = $request->session()->get('last_activity', time());
            
            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Session expired due to inactivity'], 401);
                }
                
                return redirect('/login')->with('status', 'Session expired due to inactivity');
            }
            
            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }
}