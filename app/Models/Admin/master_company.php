<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class master_company extends Model
{
    protected $table = 'core_company';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'company_name',
        'company_code',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_company', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }

    public function department()
    {
        return $this->hasMany(master_department::class, 'CompanyId');
    }

    public function designation()
    {
        return $this->hasMany(master_designation::class, 'CompanyId');
    }
}
