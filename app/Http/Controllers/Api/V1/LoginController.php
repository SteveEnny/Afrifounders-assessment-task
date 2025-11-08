<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['IThe provided credentials are incorrect.'],
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('API token for '.$user->email,
            ['*'],
            now()->addHour())->plainTextToken;
        $data = [
            'token' => $token,
            'type' => 'Bearer',
        ];

        return $this->ok('Authenticated', $data);
    }
}
