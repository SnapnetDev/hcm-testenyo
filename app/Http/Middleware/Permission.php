<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$perm)
    {

        $permissions=\Auth::user()->role->permissions;
        $count=$permissions->where('constant', $perm)->count();
        if($count<1)
        {
            // throw new \Exception("Your Role Id : ". $role);
            return redirect()->route('home');
            // return 1;
            // return abort(403);
        }         
        return $next($request);
    }
}
