<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\LoginRequest;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = $this->userRepository->getUserByEmail($request->get('email'));

        if (!$user || !Hash::check($request->get('password'),  $user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $token = $user->createToken('Auth Token')->accessToken;

        $data = [
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        return response($data);
    }

    public function getLogin()
    {
        return response([
            'message' => 'You need to login.'
        ], 401);
    }
}
