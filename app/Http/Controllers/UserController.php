<?php

namespace App\Http\Controllers;

use App\User;
use App\Transaksi;//
use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'jumlah_saldo' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'jumlah_saldo' => $request->get('jumlah_saldo'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function saldo(Request $request){
        $username = $request->username;
        $user = User::where('username', $username)->first();

        $user->jumlah_saldo = $user->jumlah_saldo + $request->input('jumlah');
        $user->save();
        $pesan = "alhamdulillah berhasil";
        return response()->json(compact('user', 'pesan'));
        
       
    }
}
