<?php

namespace App\Services;

use App\Repositories\ActivityRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private UserRepository $userRepo;

    private ActivityRepository $activityRepo;

    public function __construct(UserRepository $userRepo, ActivityRepository $activityRepo)
    {
        $this->userRepo = $userRepo;
        $this->activityRepo = $activityRepo;
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

        $this->activityRepo->storeActivity($user->id, 'LOGIN');

        return [
            'token' => $token->plainTextToken,
        ];
    }

    public function logout(Request $request): void
    {
        $this->activityRepo->storeActivity($request->user()->id, 'LOGOUT');
        $request->user()->currentAccessToken()->delete();
    }
}
