<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateAccountRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(): Response
    {
        return inertia('Profile/Show');
    }

    public function edit(): Response
    {
        return inertia('Profile/Edit');
    }

    public function updateAccount(UpdateAccountRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('account-updated', 'Saved.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        $user->update([
            'password' => $validated['password']
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('password-updated', 'Saved.');
    }
}
