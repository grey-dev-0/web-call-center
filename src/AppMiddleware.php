<?php

namespace GreyZero\WebCallCenter;

use Illuminate\Auth\Middleware\Authenticate;

class AppMiddleware extends Authenticate{
    /**
     * @inheritdoc
     */
    public function handle($request, \Closure $next, ...$guards){
        $this->authenticate($request, array_keys(config('auth.guards')));
        $path = request()->path();
        if(!empty($prefix = config('web-call-center.prefix')))
            $path = str_replace("$prefix/", '', $path);
        if(!\Str::startsWith($path, auth()->user()->authenticatable_type))
            abort(403);

        return $next($request);
    }
}
