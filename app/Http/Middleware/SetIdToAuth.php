<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class SetIdToAuth {
        public function handle(Request $request, Closure $next) {
            $request->route()->setParameter('user', Auth::id());
            return $next($request);
        }
    }
