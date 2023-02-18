<?php

namespace App\Models;

use App\Enums\AbsenceCause;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceLog extends Model
{
    protected $fillable = [
        'cause',
        'is_validated',
        'attendance_id',
    ];

    protected $casts = [
        'cause' => AbsenceCause::class,
        'is_validated' => 'boolean',
    ];

    public $timestamps = false;

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    public function isFullDay(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->duration >= 480
        );
    }
}
