<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'device' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken($request->device)->plainTextToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'Login successfully.');
        }
        return $this->sendError('Unauthorized.', ['error' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'device' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $user = User::create($data);
        $role = Role::guest();
        $role->users()->save($user);
        $success['token'] = $user->createToken($request->device)->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'Register successfully.');
    }
}
