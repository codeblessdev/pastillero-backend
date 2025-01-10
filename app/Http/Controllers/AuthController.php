<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro de usuarios
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:autonomous,caregiver,dependent',
            'age' => 'required|integer|min:0',
            'avatarUrl'   => 'nullable|string',
            'borderColor' => 'nullable|string',  
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'age' => $validated['age'],
            'avatar_url'   => $validated['avatarUrl'] ?? null,
            'border_color' => $validated['borderColor'] ?? null,
        ]);

        // return response()->json(['user' => $user], 201);
        return response()->json([
            'message' => 'Usuario registrado con éxito.',
            'user' => $user
        ], 201);
    }

    // Inicio de sesión
    // public function login(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    
    //     // Usa solo las credenciales necesarias para el intento de autenticación
    //     if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
    //         throw ValidationException::withMessages([
    //             'email' => ['Las credenciales proporcionadas no son correctas. Email: '],
    //         ]);
    //     }
    
    //     $user = Auth::user();
    //     $token = $user->createToken('auth_token')->plainTextToken;
    
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => $user,
    //     ]);
    // }

    public function login(Request $request)
    {
        // Validar inputs
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // 1) Verificar si existe el usuario por el email
        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            // 404 = Recurso (usuario) no encontrado
            return response()->json([
                'message' => 'Este usuario no existe'
            ], 404);
        }
    
        // 2) Verificar contraseña
        if (!Hash::check($validated['password'], $user->password)) {
            // 401 = No autorizado (credenciales inválidas)
            return response()->json([
                'message' => 'La contraseña es incorrecta'
            ], 401);
        }
    
        // 3) Iniciar sesión (opcionalmente, si requieres que Auth::user() devuelva este usuario)
        Auth::login($user);
    
        // 4) Generar token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // 5) Retornar la respuesta exitosa (200 por defecto)
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }
    

    // Cierre de sesión
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    // Detalles del usuario autenticado
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|min:8',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->token, $user->password_reset_token)) {
        return response()->json(['error' => 'Token inválido'], 400);
    }

    $user->password = Hash::make($request->password);
    $user->password_reset_token = null;
    $user->save();

    return response()->json(['message' => 'Contraseña restablecida exitosamente']);
}

}

