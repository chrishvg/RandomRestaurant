<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'created_at'
    ];

    protected $hidden = [
        'updated_at'
    ];
}
