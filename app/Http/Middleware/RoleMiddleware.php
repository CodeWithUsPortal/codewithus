<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use User;
use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next, $roles)
    {   
        // If User is not Authenticated
        if($this->auth->guest()){
            return abort(403);
        }
        else{ // If user is authenticated but not approved yet
           
            if($request->user()->hasRole(explode('|',$roles)) == false){
                //return abort(403);
                return redirect()->route('home');
            }
            return $next($request);
           
        }
    }
}
