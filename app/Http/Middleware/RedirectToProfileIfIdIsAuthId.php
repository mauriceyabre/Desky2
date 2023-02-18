<?php

    namespace App\Http\Middleware;

    use Auth;
    use Closure;
    use Illuminate\Http\Request;

    class RedirectToProfileIfIdIsAuthId {
        public function handle(Request $request, Closure $next) {
            /** @noinspection PhpUndefinedFieldInspection */
            if ($request->id == Auth::id()) {
                $destination = collect(explode('/', $request->path()))->last();
                return redirect()->route('profile.'.$destination);
            }
             return $next($request);
        }
    }
