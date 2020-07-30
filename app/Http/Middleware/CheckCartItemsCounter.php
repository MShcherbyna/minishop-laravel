<?php

namespace App\Http\Middleware;

use Closure;

class CheckCartItemsCounter
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
        $itemsContent = \Cart::getContent();

        if ($itemsContent->count() == 0 ) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
