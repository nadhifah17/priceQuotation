<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessRate extends Model
{
    const KEY_TEMPLATE_1 = 'Template Process Item';
    const KEY_TEMPLATE_2 = "Don't delete this Header or change the column format";

    use HasFactory;
    protected $table = 'process_rate';
    protected $fillable = [
        'process_id',
        'process_code_id',
        'rate'
    ];

    public function code():BelongsTo
    {
        return $this->belongsTo(ProcessCode::class, 'process_code_id', 'id');
    }

    public function group():BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }
}
