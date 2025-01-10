<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'relationship',
        'avatar_url',
        'border_color',
        'requires_password',
        'caregiver_id' 
    ];
}
