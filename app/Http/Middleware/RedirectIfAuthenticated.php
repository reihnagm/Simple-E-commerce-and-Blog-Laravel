<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectIfAuthenticated
{

    public function handle($request, Closure $next, $guard = null)
    {

        switch ($guard) {
        case 'admin':
          if (Auth::guard($guard)->check()) {
              return redirect(route('admin.dashboard'));
          }
          break;

        default:
          if (Auth::guard($guard)->check()) {
              return redirect('/');
          }
          break;
      }

        return $next($request);
        
    }

}
