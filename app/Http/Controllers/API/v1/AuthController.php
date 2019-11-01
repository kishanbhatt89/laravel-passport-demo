<?php

namespace App\Http\Controllers\API\v1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /** @var User */
    private $user;

    public function register(RegisterUser $request)
    {
        $this->user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->createUserAccessTokenResponse();
    }

    public function login(LoginUser $request)
    {
        if (Auth::attempt($request->validated())) {
            $this->user = Auth::user();

            return $this->createUserAccessTokenResponse();
        } else {
            return response()->json([
                'status' => 'fail',
                'data'   => [
                    'title' => __('auth.failed')
                ]
            ]);
        }
    }

    private function createUserAccessTokenResponse()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'token' => $this->user->createToken('ServiceOnDesk')->accessToken
            ]
        ]);
    }
}
