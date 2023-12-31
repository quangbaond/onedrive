<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}
