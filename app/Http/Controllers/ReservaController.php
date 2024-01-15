<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Pista;
use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    // GET - Listado de las reservas
    public function index()
    {
        try {
            $reservas = Reserva::with('user', 'pista', 'pago')->get();
            $reservaResponse = [];
            foreach ($reservas as $reserva) {
                $array = [
                    'id' => $reserva['id'],
                    'fecha_reserva' => $reserva['fecha_reserva'],
                    'hora_reserva' => $reserva['hora_reserva'],
                    'tiene_luz' => $reserva['tiene_luz'],
                    'usuario' => $reserva['user'],
                    'pista' => $reserva['pista'],
                    'pago' => $reserva['pago'],
                    //'created_at'=> $reserva['created_at'],
                    //'updated_at'=> $reserva['updated_at'],
                ];
                array_push($reservaResponse, $array);
            }
            return ApiResponse::success('Lista de reservas', 200, $reservaResponse);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener la lista de reservas: ' . $e->getMessage(), 500);
        }
    }

    // POST - Crear una reserva
    public function store(Request $request)
    {
        try {
            request()->validate([
                'fecha_reserva' => 'required',
                'hora_reserva' => 'required',
                'user_id' => 'required|exists:users,id'
            ]);
            $data = json_decode($request->getContent());
            $tipoPista = $data->tipo_pista;
            $numPista = $data->num_pista;

            $precio_reserva = 6;

            $pista = Pista::where('tipo_pista', '=', $tipoPista)->where('num_pista', '=', $numPista)->first();
            $pistaId = $pista->id;
            $reserva = Reserva::create([
                "fecha_reserva" => $request->fecha_reserva,
                "hora_reserva" => $request->hora_reserva,
                "tiene_luz" => $request->tiene_luz == 1 ? "SI": "NO",
                "user_id" => $request->user_id,
                "pista_id" => $pistaId
            ]);
            if($request->tiene_luz == 1){
                $precio_reserva = $precio_reserva + 1;
            }
            Pago::Create([
                "cantidad" => $precio_reserva,
                "pagado" => $request->pagado == 1 ? "SI": "NO",
                "user_id" => $request->user_id,
                "reserva_id" => $reserva->id
            ]);
            return ApiResponse::success('Reserva creada', 201, $reserva);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error al crear reserva: ' . $e->getMessage(), 422);
        }
    }

    // GET by ID
    public function show(Reserva $reserva)
    {
        try {
            //$reserva = Reserva::findOrFail($reserva->id);
            $reserva = Reserva::with('user', 'pista', 'pago')->findOrFail($reserva->id);
            $reservaResponse = [
                'id' => $reserva->id,
                'fecha_reserva' => $reserva->fecha_reserva,
                'hora_reserva' => $reserva->hora_reserva,
                'tiene_luz' => $reserva->tiene_luz,
                'usuario' => $reserva->user,
                'pista' => $reserva->pista,
                'pago' => $reserva->pago,
            ];
            return ApiResponse::success('Reserva encontrada: ', 200, $reservaResponse);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Reserva no encontrada', 404);
        }
    }

    //  PUT - Actualizar Reserva
    public function update(Request $request, Reserva $reserva)
    {
        try {
            $reserva = Reserva::findOrFail($reserva->id);
            request()->validate([
                'user_id' => 'exists:users,id',
                'pista_id' => 'exists:pistas,id',
            ]);
            $reserva->update($request->all());
            return ApiResponse::success('Reserva actualizada', 200, $reserva);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de Validacion: ', 422);
        } catch (\Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 500);
        }
    }

    // DELETE - borrar registro
    public function destroy(Reserva $reserva)
    {
        try {
            $reserva = Reserva::findOrFail($reserva->id);
            $reserva->delete();
            return ApiResponse::success('Reserva borrada', 200, $reserva);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Reserva no encontrada', 404);
        }
    }


    public function horasReserva(Request $request){
        $data = json_decode($request->getContent());
        $fechaReserva = $data->fecha_reserva;
        $tipoPista = $data->tipo_pista;
        $numPista = $data->num_pista;

        try{
            $pista = Pista::where('tipo_pista', '=', $tipoPista)->where('num_pista', '=', $numPista)->first();
            $pistaId = $pista->id;
            $horasReserva = Reserva::where('fecha_reserva', '=', $fechaReserva)
            ->where('pista_id', '=', $pistaId )->get();
            return ApiResponse::success('Horas reservadas', 200, $horasReserva);
        }catch (ModelNotFoundException $e) {
            return ApiResponse::error('No hay horas de reserva', 404);
        }

    }
}
