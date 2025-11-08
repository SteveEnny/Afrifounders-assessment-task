<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;

final class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {

            $user = User::create($request->validated());
            event(new Registered($user));

            return $this->ok('User created successfully', $user, 201);
        } catch (QueryException $ex) {
            return $this->error('Failed to register user', 500);
        }
    }
}
