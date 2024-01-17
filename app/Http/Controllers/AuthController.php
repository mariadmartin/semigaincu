<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // metodo para crear usuarios
    public function create(Request $request)
    {
        $rules = [
            "name" => "required|string|max:100",
            "email" => "required|string|email|max:100|unique:users",
            "password" => "required|string|min:8",
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()->all(),
            ], 400);
        }
        $user = User::create([
            "name" => $request->name,
            "apellidos" => $request->apellidos,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "es_admin" => $request->es_admin,
            "sexo"=> $request->sexo,
            "fecha_nacimiento"=> $request->fecha_nacimiento,
            "direccion_postal"=> $request->direccion_postal,
            "municipio"=> $request->municipio,
            "provincia"=> $request->provincia
        ]);

        return response()->json([
            "status" => true,
            "message" => "User created successfully",
            "token" => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    // metodo para iniciar sesion
    public function login(Request $request)
    {
        $rules = [
            "email" => "required|string|max:100",
            "password" => "required|string",
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()->all(),
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                "status" => false,
                "errors" => ['Unauthorized'],
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            "status" => true,
            "message" => "User logged in successfully",
            "data" => $user,
            "token" => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    // metodo para cerrar sesion - elimina los tokens generados por el usualrio
    public function logout(Request $request)
    {
        //$request->user()->currentAccessToken()->delete();
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => true,
            "message" => "User logged out successfully",
        ], 200);
    }

    // metodo para comprobar el usuario que esta autenticado
    public function logged_user()
    {
        $loggeduser = Auth::user();
        return response()->json([
            'user' => $loggeduser,
            'status' => true,
            'message' => 'Logged User Data'
        ], 200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $loggeduser = Auth::user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->save();
        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success'
        ], 200);
    }

}