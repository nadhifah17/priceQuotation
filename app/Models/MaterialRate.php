<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRate extends Model
{
    use HasFactory;
    protected $table = 'material_rate';
    protected $fillable = [
        'material_id',
        'material_spec_id',
        'period',
        'rate'
    ];

    const KEY_TEMPLATE_1 = 'Template Material Item';
    const KEY_TEMPLATE_2 = "Don't delete this Header or change the column format";

    /**
     * Relation eloquent
     */
    public function material():BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    /**
     * Relation eloquent
     */
    public function materialSpec():BelongsTo
    {
        return $this->belongsTo(MaterialSpec::class, 'material_spec_id', 'id');
    }
}
