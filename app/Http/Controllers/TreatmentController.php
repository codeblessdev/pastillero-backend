<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;
use App\Notifications\LowStockNotification;

class TreatmentController extends Controller
{
    // Listar tratamientos activos
    public function activeTreatments(Request $request)
    {
        $treatments = Treatment::where('user_id', $request->user()->id)
            ->where(function ($query) {
                $query->where('is_chronic', true)
                      ->orWhere('end_date', '>=', now());
            })
            ->get();

        return response()->json($treatments);
    }

    // Listar historial de tratamientos
    public function treatmentHistory(Request $request)
    {
        $treatments = Treatment::where('user_id', $request->user()->id)
            ->where('is_chronic', false)
            ->where('end_date', '<', now())
            ->orderBy('end_date', 'desc')
            ->get();

        return response()->json($treatments);
    }

    // Crear un tratamiento
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon_url' => 'nullable|string',
            'units_available' => 'required|integer|min:1',
            'units_per_dose' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'schedule_type' => 'required|string|in:daily,specific,alternating',
            'schedule' => 'required|array',
            'is_chronic' => 'required|boolean',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'notify_low_stock' => 'required|boolean',
            'notify_expiration' => 'required|boolean',
        ]);

        $treatment = Treatment::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'icon_url' => $validated['icon_url'],
            'units_available' => $validated['units_available'],
            'units_per_dose' => $validated['units_per_dose'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'schedule_type' => $validated['schedule_type'],
            'schedule' => json_encode($validated['schedule']),
            'is_chronic' => $validated['is_chronic'],
            'expiration_date' => $validated['expiration_date'],
            'notify_low_stock' => $validated['notify_low_stock'],
            'notify_expiration' => $validated['notify_expiration'],
        ]);

        return response()->json($treatment, 201);
    }

    // Actualizar un tratamiento
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'icon_url' => 'nullable|string',
            'units_available' => 'nullable|integer|min:1',
            'units_per_dose' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'schedule_type' => 'nullable|string|in:daily,specific,alternating',
            'schedule' => 'nullable|array',
            'is_chronic' => 'nullable|boolean',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'notify_low_stock' => 'nullable|boolean',
            'notify_expiration' => 'nullable|boolean',
        ]);

        $treatment->update(array_filter($validated));

        return response()->json($treatment);
    }


    public function updateStock(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'units' => 'required|integer|min:1'
        ]);
    
        $treatment->update([
            'units_available' => $treatment->units_available + $validated['units'],
        ]);
    
        // Notify if stock is still low
        if ($treatment->isStockLow() && $treatment->notify_low_stock) {
            $request->user()->notify(new LowStockNotification($treatment));
        }
    
        return response()->json([
            'treatment' => $treatment,
            'message' => $treatment->isStockLow()
                ? 'Stock actualizado, pero sigue siendo bajo.'
                : 'Stock actualizado exitosamente.',
        ]);
    }
    

    // Eliminar un tratamiento
    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return response()->json(['message' => 'Tratamiento eliminado con éxito']);
    }
}
