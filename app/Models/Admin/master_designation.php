<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class master_designation extends Model
{
    use HasFactory;
    protected $table = 'master_designation';
    protected $primaryKey = 'DesigId';
    public $timestamps = false;
    protected $fillable = [
        'DesigId',
        'DesigName',
        'DesigCode',
        'DepartmentId',
        'CompanyId',
        'DesigStatus'

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('master_designation', function (Builder $builder) {
            $builder->orderBy('DesigName', 'asc');
        });
    }

    public function department()
    {
        return $this->belongsTo(master_department::class, 'DepartmentId');
    }

    public function company()
    {
        return $this->belongsTo(master_company::class, 'CompanyId');
    }
}
