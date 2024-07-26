<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create($validateData);
        $token = $user->createToken("auth_token")->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'status' => true,
            'msg' => "User Created Successfully!"

        ], 200);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $user = User::where(['email' => $validate['email']], ['password' => $validate['password']])->first();

        $token = $user->createToken("auth_token")->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'status' => true,
            'msg' => "Logged in Successfully!"

        ], 200);
    }

    public function getuser($id)
    {
        $user = User::find($id);

        if (!isNull($user)) {
            return response()->json([
                'status' => false,
                'msg' => "User Not Found!"
            ], 400);
        } else {
            return response()->json([
                'user' => $user,
                'status' => true,
                'msg' => "User Found!"
            ], 200);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'logged out',
        ]);
    }

    public function changepassword(Request $request)
    {
        Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);
        $loggeduser = auth()->user();
        dd($loggeduser);
        // $loggeduser->password = Hash::make($request->password);
        // $loggeduser->save();
        return response()->json([
            'status' => true,
            'msg' => "Password change successfully!"
        ], 200);
    }
}
