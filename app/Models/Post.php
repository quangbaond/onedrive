<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'email_contact',
        'address',
        'industry',
        'limit',
        'end_date',
        'created_by',
        'updated_by',
    ];
}
