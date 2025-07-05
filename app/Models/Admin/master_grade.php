<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_grade extends Model
{
    use HasFactory;

    protected $table = 'core_grade';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'grade_name',
        'level',
        'effective_date',
        'is_active',
        'company_id',

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_grade', function (Builder $builder) {
            $builder->orderBy('grade_name', 'asc');
        });
    }
}
