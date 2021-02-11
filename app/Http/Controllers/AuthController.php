<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\models\User;
 

class AuthController extends Controller
{
   public function signUp(Request $request)
    {
      
            if(!$request->has(['name','email','password','tipo_doc','doc','dir'])){
                return response(["estado" => false, "mensaje" => "Los atributos permitidos son [name,email,password,tipo_doc,'dir']."]);
            }

             if (!$request->filled(['name','email','password','tipo_doc','doc','dir'])) {
                return response(["estado" => false, "mensaje" => "No se permite registrar datos vacíos."]);
            }
        
            $name = is_null($request->input('name')) || $request->input('name') === "" ? null : trim(mb_strtoupper($request->input('name')));
            $email = is_null($request->input('email')) || $request->input('email') === "" ? null : trim(mb_strtoupper($request->input('email')));
             
            if(!is_null($name)) {
                if(!preg_match("/^[A-Z0-9ÁÉÍÓÚÜ.-_\s]+$/", $name)) { 
                    return response(["estado" => false, "mensaje" => "El tipo de dato es incorrecto."]); 
                }
                if (mb_strlen($name) > 50) { 
                    return response(["estado" => false, "mensaje" => "el campo nombre no cumple con las restricciones establecidas."]);
                }
            }else{
                return response(["estado" => false, "mensaje" => "No se permiten datos nulos."]);
            }
  
            $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'dir' => $request->dir,
            'doc' => $request->doc,
            'tipo_doc' => $request->tipo_doc,
            'fech_nac' => $request->fech_nac,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

       

           

           
        // User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password)
        // ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('yWerTmKcYUFVu32qA9uuGhloi8Mj9YJYO1JG7dpx');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
