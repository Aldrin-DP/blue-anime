<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create() {
        return inertia('Auth/Register');
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:2', 'max:100', 'unique:users', 'alpha_num'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        User::create($validated);

        return redirect()->route('register.success');
    }

    public function success() {
        return inertia('Auth/RegisterSuccess');
    }
}
