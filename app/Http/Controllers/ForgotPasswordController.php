<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route as FacadesRoute;

class ForgotPasswordController extends Controller
{
    
    public function forgot() {
        $credentials = request()->validate(['email' => 'required|email']);

        $reset_password_status = Password::sendResetLink($credentials);

        if($reset_password_status == Password::RESET_LINK_SENT) {
            return response()->json(["message" => 'Link para resetar a senha enviado para o seu e-mail.',
        "status" => __($reset_password_status)]);
        }

        return response()->json(["message" => 'Ops... Algo deu errado',
        "status" => __($reset_password_status)]);
        
    }


    public function reset(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $email_password_status =  Password::reset($credentials, function($user) use ($request){
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => null
            ])->save();
 
         }); 

         
 
         if($email_password_status == Password::INVALID_TOKEN) {
             return response()->json(["message" => "Token inválido"], 400);
         }
 
         if($email_password_status == Password::PASSWORD_RESET) {
            
            return redirect()->route('password.edited');
         }
        

    }

    


}
