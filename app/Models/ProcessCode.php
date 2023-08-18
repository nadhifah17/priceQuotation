<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessCode extends Model
{
    use HasFactory;
    protected $table = 'process_code';
    protected $fillable = ['name', 'process_id'];
}
