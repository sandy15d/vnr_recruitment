<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class master_department extends Model
{
    use HasFactory;

    protected $table = 'core_department';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'department_name',
        'department_code',
        'is_active'

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_department', function (Builder $builder) {
            $builder->orderBy('department_name', 'asc');
        });
    }

}
