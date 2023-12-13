<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(array $input): array
    {
        $username = $input['username'];
        $password = $input['password'];
        $user = $this->userRepo->getUserByUsername($username);
        if (empty($user)) {
            throw new AuthenticationException('invalid username or password');
        }

        if (!Hash::check($password, $user->password)) {
            throw new AuthenticationException('invalid username or password');
        }

        $token = $user->createToken('access_token');

        return [
            'token' => $token->plainTextToken,
        ];
    }
}
