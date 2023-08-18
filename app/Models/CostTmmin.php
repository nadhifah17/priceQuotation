<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostTmmin extends Model
{
    use HasFactory;
    protected $table = 'cost_tmmin';
    protected $fillable = ['name', 'number', 'total_cost'];
}
