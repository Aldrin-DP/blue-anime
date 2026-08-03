<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
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
