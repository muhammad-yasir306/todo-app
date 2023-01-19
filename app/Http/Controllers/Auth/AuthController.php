<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ErrorResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Responses\SuccessResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * register
     *
     * @param  mixed $registerRequest
     * @return 
     */
    public function register(RegisterRequest $registerRequest)
    {
        try {
            $validated = $registerRequest->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        
            $user->sendEmailVerificationNotification();

            return (new SuccessResponse())->toResponse('Check your email for verification link', ['user' => $user]);
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }

    /**
     * Login
     *
     * @param  mixed $loginRequest
     * @return 
     */
    public function login(LoginRequest $loginRequest)
    {
        try {
            $validated = $loginRequest->validated();

            $user = User::where('email', $validated['email'])->first();
 
            if (! $user || ! Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            return (new SuccessResponse())->toResponse('Successfully logged in!', ['token' => $user->createToken(config('app.name'))->plainTextToken]);
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }
    
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        try {
            // Revoke all tokens...
            auth()->user()->tokens()->delete();
            return (new SuccessResponse())->toResponse('Successfully logged out!');
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }
}
