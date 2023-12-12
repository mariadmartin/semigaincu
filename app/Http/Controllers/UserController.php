<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Expr\Cast\String_;

class UserController extends Controller
{
    // GET obtener todos los Usuarios
    public function index()
    {
        try {
            $usuarios = User::all();
            return ApiResponse::success('Lista de usuarios', 200, $usuarios);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener la lista de usuarios: ' . $e->getMessage(), 500);
        }
    }

    // POST - Creacion Usuario
    public function store(Request $request)
    {
        try {
            request()->validate([
                'name' => 'required|string|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:100',
                'apellidos' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
                'email' => 'required|string|email:rfc,dns|unique:users',
                'password' => 'required|string|min:8',
                'numero_socio' => 'required|unique:users',
                'fecha_alta' => 'required',
                'es_admin' => 'required|string|min:1|max:2',
                'sexo' => 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'
            ]);
            $usuarios = User::create($request->all());
            return ApiResponse::success('Usuario creado', 200, $usuarios);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            return ApiResponse::error('Error de Validacion: ', 422, $errors);
        }
    }

    // GET by ID - mostrar un usuario
    public function show(string $id)
    {
        try {
            $usuario = User::findOrFail($id);
            return ApiResponse::success('Usuario obtenido', 200, $usuario);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Usuario no encontrado:  ' . $e->getMessage(), 404);
        }

    }

    // PUT - Actualizar usuario
    public function update(Request $request, string $id)
    {
        try {
            $usuario = User::findOrFail($id);
            request()->validate([
                'name' => 'string|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:100',
                'apellidos' => 'string|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
                //'email' => 'string|email:rfc,dns|unique:users',
                //'password' => 'string|min:8',
                //'numero_socio' => 'unique:users',
                //'fecha_alta' => 'date',
                'es_admin' => 'string|min:1|max:2',
                'sexo' => 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'
            ]);
            $usuario = $usuario->update($request->all());
            return ApiResponse::success('Usuario actualizada correctamente', 200, $usuario);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Usuario no encontrada ', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        }
    }

    // DELETE - borrar usuario
    public function destroy(string $id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            return ApiResponse::success('Usuario borrado', 200, $usuario);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Usuario no encontrada ', 404);
        }
    }
}