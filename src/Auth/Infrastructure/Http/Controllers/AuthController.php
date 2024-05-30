<?php

namespace TeacherAi\Auth\Infrastructure\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use TeacherAi\Payment\Application\DTO\InputCreateClient;
use TeacherAi\Payment\Application\Service\ClientService;

class AuthController
{
    public function __construct(
        private ClientService $clientService
    ) {
    }

    public function register(Request $request)
    {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        try {
//            $client = $this->clientService->create(new InputCreateClient($validatedData['name'], $validatedData['cpf']));

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
//                'cpf' => $validatedData['cpf'],
//                'external_id' => $client->id,
                'password' => Hash::make($validatedData['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
