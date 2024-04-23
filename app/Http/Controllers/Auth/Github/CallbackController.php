<?php

namespace App\Http\Controllers\Auth\Github;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class CallbackController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();
        $user       = User::updateOrCreate([
            'nickname' => $githubUser->getNickname(),
            'email'    => $githubUser->getEmail(),
        ], [
            'name'              => $githubUser->getName(),
            'email_verified_at' => now(),
            'password'          => Hash::make(Str::random(40)),
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
