<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 9/25/2017
 * Time: 11:38 AM
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class CEUserController extends Controller
{
    /**
     *  API login for user
     * Authenticates user
     */
    public function login(){

        $content = [];

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

            $user = Auth::user();
            $data = $user->createToken($user->id);

            $content['token'] = $data->accessToken;
            $content['expires_at'] = $data->token->expires_at;
            $content['user_id'] = $user->id;
            $status = 200;
        }

        else{
            $content['error'] = "Unauthorised";
            $status = 401;
        }

        return response()->json($content, $status);
    }

    /**
     *  returns user login data
     */
    public function details(){
        return response()->json(['user' => Auth::user()]);
    }
}