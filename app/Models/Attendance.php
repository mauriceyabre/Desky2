<?php

namespace App\Models;

use App\Enums\AbsenceCause;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attendance extends Model {
    protected $fillable = [
        'date',
        'absence',
        'user_id'
    ];
    protected $casts = [
        'absence' => AbsenceCause::class,
        'date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    // protected $with = ['presenceLogs'];
    // protected $appends = ['presence_duration', 'absence_duration', 'has_active_session'];

    // RELATIONS
    public function presenceLogs() : HasMany {
        return $this->hasMany(PresenceLog::class);
    }

    public function latestPresenceLog() : HasOne {
        return $this->hasOne(PresenceLog::class)->ofMany(['started_at' => 'max']);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function projects() : BelongsToMany {
        return $this->BelongsToMany(Project::class)->withPivot('duration');
    }


    // ACCESSORS AND MUTATORS
    public function presenceDuration() : Attribute {
        return Attribute::make(get: fn() => (!!$this->presenceLogs->count()) ? $this->presenceLogs->sum('duration') : 0);
    }

    public function absenceDuration() : Attribute {
        return Attribute::make(get: fn() => !!$this->absence ? 480 - $this->presenceDuration : 0);
    }

    public function hasActiveSession() : Attribute {
        return Attribute::make(get: fn() => !!$this->latestPresenceLog?->is_active);
    }
}
