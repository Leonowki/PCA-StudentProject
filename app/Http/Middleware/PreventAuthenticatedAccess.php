<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\Log;

class PreventAuthenticatedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check() && in_array($request->route()->getName(), ['payroll.login', 'login'])) {
            // Redirect authenticated user to the dashboard based on their role
            if (Auth::user()->user_level === 'admin'){    
                dd([
                    'route_name' => $request->route()->getName(),
                    'middleware_triggered' => 'prevent.auth.access',
                    'user' => Auth::user(),
                ]);
                return redirect()->route('admin.dashboard');
            }
            else if (Auth::user()->user_level === 'employee'){
                return redirect()->route('employee.dashboard');
            }
            else if (Auth::user()->user_level === 'bioadmin') {
                dd([
                    'route_name' => $request->route()->getName(),
                    'middleware_triggered' => 'prevent.auth.access',
                    'user' => Auth::user(),
                ]);
                return redirect()->route('bioadmin.dashboard');
            }
        }

        
        return $next($request);
    }
}
