<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        if(!isset(\Auth::user()->name)){
            return redirect('/home/login/index');
        }
        return $next($request);
    
    }
}
