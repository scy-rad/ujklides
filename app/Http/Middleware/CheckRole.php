<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
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
        
       
        if (($request->user() === null) && (Auth::viaRemember() === FALSE)){
            return redirect('/login');
        }

        $actions = $request->route()->getAction();
        //echo '<pre>';
        //var_dump($actions);
        //echo '</pre>';


        $roles = isset($actions['roles']) ? $actions['roles'] : null; //jeżeli jest akcja "roles" to ją przypisujemy, a jak nie to NIE :)

        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }
        ?>
        

    
        <?php
        return $next($request);
        //return redirect('/login');
    }
}
