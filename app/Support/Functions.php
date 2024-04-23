<?php

use App\Models\User;

function user(): User | null
{
    if (auth()->check()) {
        return auth()->user();
    }

    return null;
}
