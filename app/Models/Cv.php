<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Cv extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'note',
        'address',
        'birthday',
        'cv',
        'address',
        'industry',
        'experience',
        'salary',
        'position',
        'level',
        'language',
        'skill',
        'avatar',
        'interview_time',
        'interview_result',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable);
    }

    public function groups(): BelongsToMany
    {
        return  $this->belongsToMany(Group::class, 'cv_groups');
    }
}
