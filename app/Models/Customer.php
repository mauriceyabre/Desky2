<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {
    use HasFactory, HasAdvancedFilter, SoftDeletes;

    public $fillable = [
        'ref_id',
        'type',
        'name',
        'vat_number',
        'phone',
        'email',
        'pec',
        'vat',
        'tax_code',
        'pec',
        'sdi_code',
        'e_invoice',
        'default_vat_id',
        'default_vat_entity',
        'description',
        'bank_iban',
        'bank_name',
        'bank_swift',
        'category',
        'website',
        'social_links',
        'created_by'
    ];

    protected $with = [
        'address'
    ];

//    protected $appends = [
//        'projects_count',
//        'active_projects_count',
//        'completed_projects_count',
//        'time_bounded_projects_count'
//    ];

    public $casts = [
         'address' => 'json',
         'social_links' => 'json'
    ];

    public function creator() : BelongsTo {
        return $this->belongsTo(User::class, 'created_by')
            ->withoutEagerLoads()
            ->select(['id', 'first_name', 'last_name']);
    }

    public function address() : MorphOne {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function projects() : HasMany {
        return $this->hasMany(Project::class);
    }

    public function projectsCount() : Attribute {
        $result = $this->projects()->count();
        return Attribute::make(get: fn() => $result);
    }

    public function activeProjectsCount() : Attribute {
        $result = $this->projects()->active()->count();
        return Attribute::make(get: fn() => $result);
    }

    public function completedProjectsCount() : Attribute {
        $result = $this->projects()->completed()->count();
        return Attribute::make(get: fn() => $result);
    }

    public function timeBoundedProjectsCount() : Attribute {
        $result = $this->projects()->timeBounded()->count();
        return Attribute::make(get: fn() => $result);
    }
}
