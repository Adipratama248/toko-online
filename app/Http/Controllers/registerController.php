<?php

namespace App\Http\Controllers;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class registerController extends Controller
{
    public function showRegistration()
    {
        return view('register');
    }
    public function register(Request $request)
    { 

        $validator = Validator::make($request->all(),[
            'username'  => 'required',
            'role'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'email' => $request->email,
            'password'  => bcrypt($request->password)
        ]);

                //return response JSON user is created
                if($user) {
                    return response()->json([
                        'success' => true,
                        'user'    => $user,  
                    ], 201);
                    // return redirect()->route('register')->with('success', 'Registrasi berhasil! Silakan login.');
                }

                return response()->json([
                    'success' => false,
                ], 409);
    }
}
    