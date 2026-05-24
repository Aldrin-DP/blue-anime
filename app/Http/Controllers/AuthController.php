<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {

    $validated = $request->validate([
        'username' => ['required', 'string', 'min:2', 'max:100', 'unique:users', 'alpha_num'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed']
    ]);

    User::create($validated);

    return redirect()->route('register.success');

    }
}
