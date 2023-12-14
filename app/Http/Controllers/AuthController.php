<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Validator;

use App\Models\User;
use \stdClass;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        /*El validator se encargará que todos los 
        campos requeridos cumplan con las especificaciones*/
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        /*Si la validación falla el json nos devolverá 
         un mensaje de error*/
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        //Si no falla la validacion crearemos el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        /*Se crea el token de autenticación
         que devolveremos en la petición*/
        $token = $user->createToken('auth_token')->plainTextToken;

        /*Devolveremos la respuesta de todos los datos
         del usuario en formato json
        */
        return response()
            ->json(['data' => $user,'acces_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        /*Intentará realizar una inicio de sesion con 
         el email y password dados*/
         //Si no puede acceder nos dirá que nos estamos autorizados
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()
            ->json(['mesaje' => 'Unauthorized'],401);
        }

        /*Si, si existe buscaremos en nuestra bdd el usuario que tenga
         ese email*/
        $user = User::where('email',$request['email'])->firstOrFail();

        /*Crearemos el token */
        $token = $user->createToken('auth_token')->plainTextToken;

        //Enviaremos un token con toda la informacion del usuario
        return response()
            ->json([
                'message' => 'Hi'.$user->name,
                'accessToke' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                
            ]);
    }

    //Todos los tokens del usuario será borrados
    //Y nos mostrará que hemos cerrado sesión
    public function logout(){
        /*auth()->user()->tokens()->delete();*/
        Auth::user()->tokens()->delete();
        return [
            'message' => 'Has cerrado sesion con exito'
        ];
    }

    
}
