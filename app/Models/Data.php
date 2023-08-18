<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $table = 'data';
    protected $fillable = ['name', 'price', 'quality', 'service'];

    public function spkId():BelongsTo
    {
        return $this->belongsTo(CostTmmin::class, 'cost_id', 'id');
    }
}
