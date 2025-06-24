<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_institute extends Model
{
    use HasFactory;
    protected $table = 'master_institute';
    protected $primaryKey = 'InstituteId';
    public $timestamps = false;
    protected $fillable = [
        'InstituteId',
        'InstituteName',
        'InstituteCode',
        'StateId',
        'DistrictId',
        'Category',
        'Type',
        'Status',

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('master_institute', function (Builder $builder) {
            $builder->orderBy('InstituteName', 'asc');
        });
    }
}
