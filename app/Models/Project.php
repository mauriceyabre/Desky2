<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Project extends Model {
    use HasFactory, Notifiable, SoftDeletes;

    public static array $PHASES = [
        'deal' => ['title' => 'Opportunità'],
        'new' => ['title' => 'Nuovo'],
        'ongoing' => ['title' => 'In Corso'],
        'review' => ['title' => 'In Revisione'],
        'approved' => ['title' => 'Approvato'],
        'delivered' => ['title' => 'Consegnato'],
        'completed' => ['title' => 'Completato']
    ];

    protected $fillable = [
        'name',
        'description',
        'phase',
        'index',
        'quote',
        'deposit',
        'deadline',
        'customer_id',
        'created_by',
        'started_at',
        'delivered_at',
        'completed_at',
    ];

    protected $dates = [
        'deadline',
        'started_at',
        'delivered_at',
        'completed_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
         'balance'
    ];

    public $with = ['members:id,first_name,last_name', 'customer:id,name,email,phone,address,country_code', 'creator:id,first_name,last_name'];


    // RELATIONSHIPS
    public function members() : BelongsToMany {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function creator() : BelongsTo {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function attendances() : BelongsToMany {
        return $this->BelongsToMany(Attendance::class)->withPivot('duration');
    }


    // ACCESSORS AND MUTATORS
    public function getBalanceAttribute(): int {
        return $this->quote - $this->deposit;
    }


    // SCOPES
    public function scopeCompleted(Builder $query) : Builder {
        return $query->whereNotNull('completed_at');
    }

    public function scopeUncompleted(Builder $query) : Builder {
        return $query->whereNull('completed_at');
    }

    public function scopeActive(Builder $query) : Builder {
        return $query->whereNull('completed_at')->where('phase', '!=', 'deal');
    }

    public function scopeOngoing(Builder $query): Builder {
        return $query->whereNull('completed_at')->where('phase', '=', 'ongoing');
    }

    public function scopeKanbanized($query) {
        return $query->active()->orderBy('phase')->orderBy('index');
    }

    public function scopeArchived(Builder $query) : Builder {
        return $query->onlyTrashed()->orderBy('deleted_at');
    }

    public function scopeStarted(Builder $query) : Builder {
        return $query->whereNotNull('started_at');
    }
    public function scopeDelivered(Builder $query) : Builder {
        return $query->whereNotNull('delivered_at');
    }

    public function scopeTimeBounded($query) : Builder {
        return $query->active()->whereNotNull('deadline')->orderBy('deadline');
    }

}
