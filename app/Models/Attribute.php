<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'values',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'values' => 'array',
    ];
}
