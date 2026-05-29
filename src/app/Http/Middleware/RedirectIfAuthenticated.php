<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request \, Closure \, string ...\): Response
    {
        \ = empty(\) ? [null] : \;

        foreach (\ as \) {
            if (Auth::guard(\)->check()) {
                return redirect()->route('dashboard');
            }
        }

        return \(\);
    }
}
