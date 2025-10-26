<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class BaseTenantController extends Controller
{
    /**
     * The workspace owner whose dashboard/profile we're in.
     */
    protected User $workspaceOwner;

    /**
     * The viewer (current auth user).
     */
    protected User $viewer;

    public function __construct(Request $request)
    {
        $this->viewer = Auth::user();

        $username = $request->route('username'); // {username} from route
        $this->workspaceOwner = User::where('username', $username)
            ->firstOrFail();
    }
}
