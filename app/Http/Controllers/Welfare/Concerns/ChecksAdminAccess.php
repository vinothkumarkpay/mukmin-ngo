<?php

namespace App\Http\Controllers\Welfare\Concerns;

use App\Services\Welfare\AdminAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ChecksAdminAccess
{
    protected function access(): AdminAccessService
    {
        return app(AdminAccessService::class);
    }

    protected function adminUser()
    {
        return Auth::user();
    }

    protected function authorizePermission(string $permission): void
    {
        if (! $this->access()->userCan($this->adminUser(), $permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }

    protected function authorizeSubmission(string $type, string $action): void
    {
        if (! $this->access()->userCanSubmission($this->adminUser(), $type, $action)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }

    protected function authorizeTabAccess(string $tabId): void
    {
        if (! $this->access()->userCanAccessTab($this->adminUser(), $tabId)) {
            abort(403, 'You do not have permission to access that section.');
        }
    }
}
