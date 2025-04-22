<?php

namespace App\Http\Middleware;

use App\Traits\HandleResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    use HandleResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if($user && $user->role == 'admin')
        {
            return $next($request);
        }
        return $this->errorsMessage(['error' => __('messages.check_is_admin')]);
    }
}
