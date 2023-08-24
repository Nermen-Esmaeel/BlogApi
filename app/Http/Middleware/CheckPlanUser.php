<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPlanUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user instanceof User)
            if ($user->subscription_plan == 'free') {
                $request->merge(["post_type" => "free"]);
                return $next($request);
            }else{
                $request->merge(["post_type" => "paid"]);
                return $next($request);
            }

        
    }
}
