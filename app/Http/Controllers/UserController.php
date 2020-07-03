<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController
{
    /**
     * User Register
     */
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // insert to DB
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $accessToken = $user->createToken('patricia')->accessToken;

        // return response
        $response = [
            'success' => true,
            'message' => 'User registration successful',
            'accessToken' => $accessToken,
        ];
        return response()->json($response, 200);
    }

    /**
     * User Login
     */
    public function login(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // return response
            $response = [
                'success' => true,
                'message' => 'User login successful',
                'accessToken' => Auth::user()->createToken('patricia')->accessToken
            ];
            return response()->json($response, 200);
        } else {
            // return response
            $response = [
                'success' => false,
                'message' => 'Invalid email or password',
            ];
            return response()->json($response, 401);
        }
    }
}
