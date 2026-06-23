<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class LoginController extends Controller
{
    public function create(): Response
    {
        return inertia('Auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $request->ensureIsNotRateLimited();

        if (Auth::attempt($credentials)) {
            $request->clearRateLimit();
            $request->session()->regenerate();

            return redirect()->to(
                $request->input('redirect', route('home'))
            );
        }

        $request->recordFailedAttempt();

        return redirect()->route('login')->withErrors([
            'email' => 'Email or password is incorrect.',
        ]);
    }
}
