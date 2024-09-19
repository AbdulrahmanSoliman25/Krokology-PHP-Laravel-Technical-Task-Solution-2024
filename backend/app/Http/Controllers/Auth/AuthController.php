<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Interfaces\IUserRepository;

class AuthController extends Controller
{

    /**
     * Constructor for AuthController.
     *
     * @param IUserRepository $repository
     */
    public function __construct(protected IUserRepository $repository)
    {
    }
    /**
     *user register
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     * @auth A.Soliman
     */
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->repository->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return JsonResponse::respondSuccess('register_success_msg', '', 201);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     *user login
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     * @auth A.Soliman
     */
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->repository->findBy('email', $validated['email']);
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return JsonResponse::respondError('invalid_credentials_msg', 401);
            }

            Auth::login($user);
            $token = $user->createToken('krokology')->plainTextToken;

            $user['token'] = $token;
            return JsonResponse::respondSuccess('login_success_msg', new LoginResource($user));
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     *user logout
     *     *
     * @return JsonResponse
     * @auth A.Soliman
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return JsonResponse::respondSuccess('logout_success_msg');
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
