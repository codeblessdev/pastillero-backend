<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    // Obtener preferencias del usuario autenticado
    public function getPreferences()
    {
        $preferences = Auth::user()->preference;
        return response()->json($preferences);
    }

    // Actualizar preferencias del usuario autenticado
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'notify_low_stock' => 'boolean',
            'notify_expiration' => 'boolean',
            'theme' => 'string|max:20'
        ]);

        $preferences = $user->preference()->updateOrCreate([], $validatedData);

        return response()->json($preferences);
    }
}
