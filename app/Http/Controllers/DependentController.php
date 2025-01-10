<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DependentController extends Controller
{
    // Listar dependientes de un cuidador
    public function index(Request $request)
    {
        $dependents = Dependent::where('caregiver_id', $request->user()->id)->get();
        return response()->json($dependents);
    }

    public function getByCaregiverId(Request $request, $caregiverId)
    {
        $dependents = Dependent::where('caregiver_id', $caregiverId)->get();
        return response()->json($dependents);
    }
    

    // Registrar un dependiente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'relationship' => 'required|string|max:255',
            'avatarUrl'   => 'nullable|string',
            'borderColor' => 'nullable|string',  
            'requiresPassword' => 'required|boolean',
            // 'username' => 'nullable|required_if:requires_password,true|unique:dependents,username',
            // 'password' => 'nullable|required_if:requires_password,true|min:8',
        ]);

        $dependent = Dependent::create([
            'caregiver_id' => $request->user()->id,
            'name' => $validated['name'],
            'age' => $validated['age'],
            'relationship' => $validated['relationship'],
            'avatar_url'   => $validated['avatarUrl'] ?? null,
            'border_color' => $validated['borderColor'] ?? null,
            'requires_password' => $validated['requiresPassword'],
            // 'username' => $validated['username'] ?? null,
            // 'password' => isset($validated['password']) ? Hash::make($validated['password']) : null,
        ]);

        return response()->json($dependent, 201);
    }

    // Actualizar un dependiente
    public function update(Request $request, Dependent $dependent)
    {
        $this->authorize('update', $dependent);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'relationship' => 'required|string|max:255',
            'avatar_url' => 'nullable|string',
            'border_color' => 'nullable|string',
            'requires_password' => 'required|boolean',
            // 'username' => 'nullable|required_if:requires_password,true|unique:dependents,username,' . $dependent->id,
            // 'password' => 'nullable|required_if:requires_password,true|min:8',
        ]);

        $dependent->update([
            'name' => $validated['name'],
            'age' => $validated['age'],
            'relationship' => $validated['relationship'],
            'avatar_url' => $validated['avatar_url'],
            'border_color' => $validated['border_color'] ?? '#000000',
            'requires_password' => $validated['requires_password'],
            // 'username' => $validated['username'] ?? null,
            // 'password' => isset($validated['password']) ? Hash::make($validated['password']) : $dependent->password,
        ]);

        return response()->json($dependent);
    }

    // Eliminar un dependiente
    public function destroy(Dependent $dependent)
    {
        $this->authorize('delete', $dependent);
        $dependent->delete();

        return response()->json(['message' => 'Dependiente eliminado con éxito']);
    }
}

