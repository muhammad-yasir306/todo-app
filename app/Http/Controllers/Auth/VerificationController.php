<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessResponse;

class VerificationController extends Controller
{    
    /**
     * verify
     *
     * @param  mixed $id
     * @return SuccessResponse
     */
    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->markEmailAsVerified();
        return (new SuccessResponse())->toResponse('Email verified');
    }
}