<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed'],
        ]);

        $profile = User::findOrFail(Auth::user()->id);
        $profile->password = Hash::make($request->password);
        $profile->save();

        return back()->with([
            'message' => 'Password berhasil diperbaharui',
        ]);
    }
}
