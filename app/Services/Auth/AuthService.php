<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Handle user login authentication.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Handle user logout.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
