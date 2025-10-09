<?php

// app/Support/ProfileVisibility.php
namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ProfileVisibility
{
    public static function ensureVisible(User $owner): void
    {
        if ($owner->is_public) return;

        if (! Auth::check() || Auth::id() !== $owner->id) {
            abort(404);
        }
    }
}
