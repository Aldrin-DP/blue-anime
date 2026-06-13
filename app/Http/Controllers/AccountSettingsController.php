<?php

namespace App\Http\Controllers;

class AccountSettingsController extends Controller
{
    public function show()
    {
        return inertia('Account/Settings/Show', ['id' => 'asdasd']);
    }
}
