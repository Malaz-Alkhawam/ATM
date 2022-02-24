<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    function signUp(Request $request) // addUser
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $u = DB::select("SELECT * FROM users u WHERE  u.email = '$user->email'");
        if (sizeof($u) != 0) {
            return response()->json(["message" => 'This email is already exist'], 400);
        }
        $user->save();
        return response()->json([
            "name" => $user->name,
            "token" => $user->createToken('token-name', ['server:update'])->plainTextToken
        ]);
    }


    function login(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->where('password', $password)->first();
        if ($user == null) {
            return response()->json(['message' => 'This email or password is not correct'], 400);
        } else {
            return response()->json([
                "name" => $user->name,
                "token" => $user->createToken('token-name', ['server:update'])->plainTextToken
            ]);
        }
    }
    function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => 'logged out'
        ]);
    }

    function deleteUser(Request $request)
    {
        $email = $request->input('email');
        if ($email != '' && $email != null) {
            DB::delete('delete From users where email = ?', [$email]);
            return response()->json([
                "message" => "Deleted"
            ]);
        }
        return response()->json([
            "message" => "Email is required"
        ]);
    }

    function editUserInfo(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = $request->User();
        if ($name != null && $name != '')
            $user->name = $name;
        if ($email != null && $email != '')
            if (User::where('email', $email)->get()->first() != null && $request->email != $user->email)
                return response()->json([
                    "message" => "This email is already exist"
                ], 400);
            else
                $user->email = $email;
        if ($password != null && $password != '')
            $user->password = $password;
        $user->save();
        return response()->json([
            "message" => "Edited successfuly"
        ]);
    }
}
