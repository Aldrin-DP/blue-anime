<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function show()
    {
        return inertia('Account/Settings/Show');
    }

    public function update(Request $request)
    {
        dd($request);
    }
}
