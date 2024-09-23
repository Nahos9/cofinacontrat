<?php

namespace App\Http\Middleware;

use App\Http\Traits\CustomResponseTrait;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    use CustomResponseTrait;

    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);
        if (auth()->check()) {
            $user = $request->user();
            if ($user->activated) {
                if ($user->password_change_required) {
                    if ($request->route()->getName() !== 'user.update-password' && $request->route()->getName() !== 'logout') {
                        return $this->responseError(["activated" => ["Vous devez changer votre mot de passe"]], 403);
                    }
                }
            } else {
                return $this->responseError(["activated" => ["Votre compte est désactivé"]], 403);
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
