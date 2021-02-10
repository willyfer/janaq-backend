<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class EmailController extends Controller
{
    
    public function contact(Request $request){
          $credentials = request('email');
         $emailsend = $request->email();
        $subject = "Asunto del correo test";
        $for = $credentials;
        Mail::send('email',$request->all(), function($msj) use($subject,$for){
            $msj->from("jhairferg@gmail.com","test_email");
            $msj->subject($subject);
            $msj->to($for);
        });
         return response()->json([
            'message' => 'mesaje enviado a  registrado.'+ $emailsend,
             
        ], 200);
    }
}
