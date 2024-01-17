<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PistaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetTokenController;
use App\Models\Pista;
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

// Rutas Publicas
Route::post('auth/register', [AuthController::class, 'create']); //ruta de registro
Route::post('auth/login', [AuthController::class, 'login']); //ruta login
Route::post('auth/send-reset-password-email', [PasswordResetTokenController::class, 'send_reset_password_email']);
Route::post('auth/reset-password/{token}', [PasswordResetTokenController::class, 'reset']);

Route::get('usuarioss', [UserController::class, 'index']);
Route::get('usuarioss/{$id}', [UserController::class, 'show']);

// rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('usuarios', UserController::class);
    Route::apiResource('pistas', PistaController::class);
    Route::apiResource('reservas', ReservaController::class);
    Route::apiResource('pagos', PagoController::class);
    Route::post('/reserva/horas', [ReservaController::class, 'horasReserva']);
    //para el logout
    Route::get('auth/logout', [AuthController::class, 'logout']);
    // para logged_user
    Route::get('auth/loggeduser', [AuthController::class, 'logged_user']);
    // para cambiar la contraseña
    Route::post('auth/changepassword', [AuthController::class, 'change_password']);
});
