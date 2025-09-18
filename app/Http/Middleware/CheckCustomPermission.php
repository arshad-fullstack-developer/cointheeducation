<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Super admin has all permissions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // If user has custom permissions, ONLY check custom permissions (restrictive mode)
        if ($user->custom_permissions && count($user->custom_permissions) > 0) {
            // Check for exact permission match
            if (in_array($permission, $user->custom_permissions)) {
                return $next($request);
            }
            
            // Check for module-level permission (e.g., if permission is 'dashboard.view' and user has 'dashboard')
            $moduleName = explode('.', $permission)[0];
            if (in_array($moduleName, $user->custom_permissions)) {
                return $next($request);
            }
            
            abort(403, 'Access denied. You do not have permission to access this module.');
        }

        // If no custom permissions, check role permissions
        if (!$user->hasPermission($permission)) {
            abort(403, 'Access denied. You do not have permission to access this module.');
        }

        return $next($request);
    }
}
