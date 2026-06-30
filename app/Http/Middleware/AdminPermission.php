<?php

namespace App\Http\Middleware;

use App\Services\Welfare\AdminAccessService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPermission
{
    /** @var AdminAccessService */
    private $access;

    public function __construct(AdminAccessService $access)
    {
        $this->access = $access;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::user();

        if (! $this->access->userCan($user, $permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            return redirect()
                ->route('welfare.admin.dashboard')
                ->with('error', 'You do not have permission to access that section.');
        }

        return $next($request);
    }
}
