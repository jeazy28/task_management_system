<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'password_confirmation' => 'required'
        ], [
            'first_name.required' => 0,
            'last_name.required' => 1,
            'username.required' => 2,
            'password.required' => 3,
            'password_confirmation.required' => 4,
            'password.confirmed' => 4
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->all();
            return response($firstError, 422);
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response('Account has been created.');
    }
}
