<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_vertical extends Model
{
    use HasFactory;
    protected $table = 'master_vertical';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'VerticalId',
        'CompanyId',
        'DepartmentId',
        'VerticalName',
    ];

    public function company()
    {
        return $this->belongsTo(master_company::class, 'CompanyId');
    }

    public function department()
    {
        return $this->belongsTo(master_department::class, 'DepartmentId');
    }
}
