<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dependent;
use App\Models\Treatment;

class AdminController extends Controller
{
    // Listar todos los usuarios
    public function listUsers()
    {
        $users = User::with('dependents')->get();
        return response()->json($users);
    }

    // Listar dependientes
    public function listDependents()
    {
        $dependents = Dependent::all();
        return response()->json($dependents);
    }

    // Ver estadísticas del sistema
    public function systemStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_dependents' => Dependent::count(),
            'total_treatments' => Treatment::count(),
        ];
        return response()->json($stats);
    }

    // Eliminar un usuario (incluyendo dependientes asociados)
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->dependents()->delete();
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}

