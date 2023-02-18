<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresenceLog extends Model
{
    protected $fillable = [
        'started_at',
        'ended_at',
        'attendance_id'
    ];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];
    public $timestamps = false;
    protected $appends = ['is_active'];
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->ended_at) ? $this->started_at->diffInMinutes($this->ended_at) : 0
        );
    }
    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => !!($this->started_at && !$this->ended_at)
        );
    }
}
