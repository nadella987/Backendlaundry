<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('username','password');

        try {
            if(! $token = JWTAuth::attempt($credentials)){
                return Response()->json(['error'=> 'Unauthorized'], 401);

            }
        }catch (JWTException $e){
            return Response()->json(['message'=> 'Generate token failed']);

        }
        // $data=[
        //     'token' =>$token,
        //     'user' =>JWTAuth::user()
        // ];

        $user = JWTAuth::user();
        return response()->json([
            'success' => true,
            'message' => 'Login Sukses',
            'token' =>$token,
            'user' => $user,
        ]);
        //return Response()->json(['token' => $token]);
    }
    public function loginCheck()
    {
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()) {
                return response()-> json(['message' => 'Invalid Token']);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()-> json(['message' => 'Token expired']);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()-> json(['message' => 'Invalid Token']);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()-> json(['message' => 'Token absent']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())){
            return response()->json(['message' => 'Anda sudah logout']);
        } else {
            return response()->json(['message' => 'Gagal Logout']);
        }
    }
 
}
