<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy; 

class PharmacyController extends Controller
{
    public function index()
{
    return Pharmacy::all();
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'address' => 'required',
        'phone' => 'nullable',
    ]);

    $pharmacy = Pharmacy::create($request->all());
    return response()->json($pharmacy, 201);
}

}
