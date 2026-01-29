<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next,  ...$roles)
    {
       
        $user = auth()->user();

            // Check if there is no user or the user doesn't have a role
            if (!$user || !$user->hasRole($roles)) {
                abort(403, 'Access Denied');
            }
            

            return $next($request);

        

        
        // if (!$user || !$user->role || !$role) {
        //     abort(403, 'Access Denied');
        // } elseif ($role[0] == auth()->user()->role->name && $check == "Super-Admin") {
        //     return $next($request);
        // } elseif (in_array(auth()->user()->role->name,['Super-Admin','Admin']) && $check == "Super-Admin-Admin" ) {
        //     return $next($request);
        // } elseif ($role[0] == auth()->user()->role->name && $check == "Manager") {
        //     return $next($request);
        // }elseif ($role[0] == auth()->user()->role->name && $check == "Team-leader") {
        //     return $next($request);
        // } elseif ($role[0] == auth()->user()->role->name && $check == "Client") {
        //     return $next($request);
        // }  elseif ( in_array(auth()->user()->role->name,['Executive','Intern']) && $check == "Executive-Intern") {
        //     return $next($request);
        // }

        abort(403, 'Access Denied!!!');
    }
}
