<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_id',
        'group_id'
    ];
}
