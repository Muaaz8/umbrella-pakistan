<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class imgAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            $user=auth()->user();
            if($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging')
            {
                return $next($request);
            }
            else
            {
                return redirect()->route('wrong_address');
            }
        }
        return redirect()->route('welcome_page');
    }
}
