<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_specialization extends Model
{
    use HasFactory;
    protected $table = 'master_specialization';
    protected $primaryKey = 'SpId';
    public $timestamps = false;
    protected $fillable = [
        'SpId',
        'EducationId',
        'Specialization',
        'Status',

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_country', function (Builder $builder) {
            $builder->orderBy('Specialization', 'asc');
        });
    }
}
