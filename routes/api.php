<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PistaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//ruta de registro
    // Aquí van las rutas a las que se permitirá acceso
    Route::post('auth/register', [AuthController::class, 'create']);
    //ruta login
    Route::post('auth/login', [AuthController::class, 'login']);

    // rutas protegidas por token
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('usuarios', UserController::class);
        Route::apiResource('pistas', PistaController::class);
        Route::apiResource('reservas', ReservaController::class);
        Route::apiResource('pagos', PagoController::class);
        Route::post('/reserva/horas', [ReservaController::class, 'horasReserva']);
        //para el logout
        Route::get('auth/logout', [AuthController::class, 'logout']);
    });
