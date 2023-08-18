<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyValue extends Model
{
    use HasFactory;
    protected $table = 'currency_value';
    protected $fillable = ['currency_type_id', 'currency_group_id', 'period', 'value'];
    
    const SLIDE_TYPE = 1;
    const NON_SLIDE_TYPE = 2;
    
    const SLIDE_TEXT = 'slide';
    const NON_SLIDE_TEXT = 'non-slide';

    const GROUP = ['idr', 'usd', 'jpy', 'thb'];
    const TYPES = ['slide', 'non-slide'];

    const KEY_TEMPLATE_1 = 'Template Material Item';
    const KEY_TEMPLATE_2 = "Don't delete this Header or change the column format";
}
