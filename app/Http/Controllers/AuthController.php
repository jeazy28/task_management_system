<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function accountLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Please enter your password',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Gets the first error message
            return response($firstError, 422);
        }

        if (Auth::attempt($request->only(['username', 'password']))) {
            $request->session()->regenerate();
            return response('00');
        } else {
            return response('Invalid Username or Password', 422);
        }
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');

    }
}
