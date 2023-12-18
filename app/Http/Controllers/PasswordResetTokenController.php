<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordResetToken; //añadir
use Illuminate\Support\Facades\Mail; // añadir
use App\Models\User; // añadir
use Illuminate\Support\Facades\Hash; // añadir
use Illuminate\Mail\Message; // añadir
use Illuminate\Support\Str; // añadir
use Carbon\Carbon; // añadir

class PasswordResetTokenController extends Controller
{
    public function send_reset_password_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;

        // Check User's Email Exists or Not
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response([
                'message' => 'Email doesnt exists',
                'status' => 'failed'
            ], 404);
        }

        // Generate Token
        $token = Str::random(60);

        // Saving Data to Password Reset Table
        PasswordResetToken::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        //dump("http://127.0.0.1:3000/api/auth/reset" . $token);

        // Sending EMail with Password Reset View
        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset Your Password');
            $message->to($email);
        });

        return response([
            'message' => 'Password Reset Email Sent... Check Your Email',
            'status' => 'success'
        ], 200);
    }

    public function reset(Request $request, $token)
    {
        // Delete Token older than 2 minute
        $formatted = Carbon::now()->subMinutes(2)->toDateTimeString();
        PasswordResetToken::where('created_at', '<=', $formatted)->delete();

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $passwordreset = PasswordResetToken::where('token', $token)->first();

        if (!$passwordreset) {
            return response([
                'message' => 'Token is Invalid or Expired',
                'status' => 'failed'
            ], 404);
        }

        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after resetting password
        PasswordResetToken::where('email', $user->email)->delete();

        return response([
            'message' => 'Password Reset Success',
            'status' => 'success'
        ], 200);

    }
}
