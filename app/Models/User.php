<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasFactory, Notifiable, HasAdvancedFilter, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'role_key', // super-admin admin employee
        'first_name',
        'last_name',
        'email',
        'password',
        'job',
        'phone',
        'birthday',
        'description',
        'remember_token',
        'iban',
        'tax_id',
        'vat_id',
        'last_login',
        'hire_date'
    ];

    protected $hidden = [
        'role_key',
        'password',
        'remember_token'
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'hire_date' => 'date:Y-m-d'
    ];

    protected $with = ['role', 'activeSessionAttendance'];
    protected $appends = ['is_admin'];

    protected static function booted(): void {
        static::forceDeleted(function (User $user) {
            $user->address()->forceDelete();
        });
    }

    // RELATIONS
    public function role(): BelongsTo {
        return $this->belongsTo(Role::class, 'role_key', 'key');
    }

    public function address() : MorphOne {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function projects() : BelongsToMany {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id');
    }

    public function activeProjects() : BelongsToMany {
        return $this->projects()->active();
    }

    public function ongoingProjects() : BelongsToMany {
        return $this->projects()->ongoing();
    }

    public function attendances() : HasMany {
        return $this->hasMany(Attendance::class);
    }

    public function latestAttendance() : HasOne {
        return $this->hasOne(Attendance::class)->ofMany([
            'date' => 'max',
            'created_at' => 'max'
        ]);
    }

    public function activeSessionAttendance(): HasOne {
        return $this->hasOne(Attendance::class)->whereRelation('presenceLogs', function ($query) {
            return $query->whereNull('ended_at');
        });
    }

    public function todayAttendance() : HasOne {
        return $this->hasOne(Attendance::class)->whereDate('date','=', today());
    }


    // ACCESSORS AND MUTATORS
    protected function firstName() : Attribute {
        return Attribute::make(get: fn($value) => ucwords($value), set: fn($value) => strtolower($value));
    }

    protected function lastName() : Attribute {
        return Attribute::make(get: fn($value) => ucwords($value), set: fn($value) => strtolower($value));
    }

    public function isAdmin() : Attribute {
        $result = in_array($this->role_key, ['admin', 'super-admin']);
        return Attribute::make(get: fn($value) => $result);
    }

    protected function ongoingProjectsCount() : Attribute {
        return Attribute::make(get: fn() => $this->ongoingProjects()->count());
    }

    public function scopeAdmins(Builder $query) : Builder {
        return $query->whereIn('role_key', ['super-admin', 'admin']);
    }

    public function scopeEmployees(Builder $query) : Builder {
        return $query->whereIn('role_key', ['employee']);
    }
}
