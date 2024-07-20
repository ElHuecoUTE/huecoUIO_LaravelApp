<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Doctrine\Inflector\Rules\French\Rules;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\http\JsonResponse;

class RegisterController extends Controller
{

  /* Documentation by Dante
    json example
    register:
    Url: http://localhost:8000/api/register
    {
      "name": "string",
      "email": "string",
      "password": "string"
      "password_confirmation": "string"
    }
    login:
    Url: http://localhost:8000/api/login
    {
      "email": "string",
      "password": "string"
    }
    */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request['password'] = bcrypt($request->password);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $success['token'] = $user->createToken('authToken')->plainTextToken;
        $success['name'] = $user->name;


        return $this->sendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Invalid login details', [], 401);
        }

        $user = Auth::user();
        $success['token'] = $user->createToken('authToken')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User logged in successfully.');
    }
}
