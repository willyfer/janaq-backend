<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class EmailController extends Controller
{
    
    public function contact(Request $request){

          
        $emailsend = $request->input('email');
        $subject = "Asunto del correo test";
         
        
                
                $config = [
                    "email_client" =>$emailsend
                ];
            $correoEmpresa = env('MAIL_USERNAME');
            Mail::send('Email', $config, function ($message) use ($correoEmpresa,$emailsend) {
                    
                $message->from($correoEmpresa);
                $message->subject('Notificación - [WILLY]');
                $message->to($emailsend);

            });
        
         return response()->json([
            'message' => 'mesaje enviado a  registrado.' . $emailsend,
             
        ], 200);
    }
}
