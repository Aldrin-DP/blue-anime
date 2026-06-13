<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return inertia('Auth/Register');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create($validated);

        return redirect()->route('register.success');
    }

    public function success()
    {
        return inertia('Auth/RegisterSuccess');
    }
}
