<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'address',
        'phone',
    ];
}
