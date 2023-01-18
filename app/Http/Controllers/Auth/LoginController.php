<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{    
    /**
     * register
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
}
