<?php

namespace App\Http\Controllers;

use App\Models\Pista;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PistaController extends Controller
{
    // GET - lista de pistas
    public function index()
    {
        try {
            $pistas = Pista::all();
            return ApiResponse::success('Listado de pistas', 200, $pistas);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener la lista de pistas: ' . $e->getMessage(), 500);
        }
    }

    // POST - Crear pista
    public function store(Request $request)
    {
        try {
            request()->validate([
                'tipo_pista' => 'required|string',
                'num_pista' => 'required|numeric'
            ]);
            $pista = Pista::create($request->all());
            return ApiResponse::success('Pista creado', 200, $pista);
        } catch (Exception $e) {
            return ApiResponse::error('Error creacion: ' . $e->getMessage(), 422);
        }
    }

    // GET BY ID - mostrar una pista
    public function show(Pista $pista)
    {
        try {
            $pista = Pista::findOrFail($pista->id);
            return ApiResponse::success('Pista obtenida', 200, $pista);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Pista no encontrada' . $e->getMessage(), 404);
        }
    }

    // PUT - Actualizar Pista
    public function update(Request $request, Pista $pista)
    {
        try {
            request()->validate([
                'tipo_pista' => 'string',
                'num_pista' => 'numeric'
            ]);
            $pista = Pista::findOrFail($pista->id);
            $pista->update($request->all());
            return ApiResponse::success('Pista actualizada correctamente', 200, $pista);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Pista no encontrada ', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        }
    }

    // DELETE - Borrar Pista
    public function destroy(Pista $pista)
    {
        try {
            $pista = Pista::findOrFail($pista->id);
            $pista->delete();
            return ApiResponse::success('Pista borrado', 200, $pista);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Usuario no encontrada ', 404);
        }
    }
}
