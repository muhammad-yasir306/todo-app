<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;

class RegisterController extends Controller
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
}
