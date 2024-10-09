<?php

namespace App\Services;


use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\UserRole;

class AuthService
{
    /**
     * login a user
     * @param array $credentials 
     * @return array  token and UserResource user
     * 
     */
    public function login(array $credentials)
    {
        try {
            $user = '';
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                $user = UserResource::make(auth()->user());
            }
            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (Exception $e) {
            Log::error("error in get login" . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
    /**
     * register a user
     * @param  $registerData 
     * @return array  token and UserResource user
     * 
     */
    public function register($registerData)
    {
        try {
            $user = new  User();
            $user->name = $registerData->name;
            $user->email = $registerData->email;
            $user->password = $registerData->password;
            $user->role = UserRole::CUSTOMER->value;
            $user->save();
            $token = Auth::login($user);
            $user = UserResource::make($user);
            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (Exception $e) {
            Log::error("error in  register" . $e->getMessage());
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "there is something wrong in server",
                ],
                500
            ));
        }
    }
}
