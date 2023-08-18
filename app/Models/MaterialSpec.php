<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSpec extends Model
{
    use HasFactory;
    protected $table = 'material_specs';
    protected $fillable = [
        'material_id',
        'specification'
    ];
}
