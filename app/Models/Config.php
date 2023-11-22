<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_secret',
        'redirect_uri',
        'access_token',
        'refresh_token',
    ];
}
