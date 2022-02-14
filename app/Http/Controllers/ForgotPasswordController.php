<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    
    public function forgot() {
        $credentials = request()->validate(['email' => 'required|email']);

        Password::sendResetLink($credentials);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            });

        return response()->json(["message" => 'Link para resetar a senha enviado para o seu e-mail.']);
    }


    public function reset() {

        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required|string|max:25|confirmed',
            'token' => 'required|string'
        ]);

        $email_password_status =  Password::reset($credentials, function($user, $password){
            $user->password = $password;
            $user->save();
        }); 

        if($email_password_status == Password::INVALID_TOKEN) {
            return response()->json(["message" => "Token inválido"], 400);
        }

        return response()->json(["message" => "Senha modificada com sucesso!"]);

    }




}
