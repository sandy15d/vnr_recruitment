<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hq_Vertical_Region extends Model
{
    use HasFactory;

    protected $table = 'master_hq_vertical_region';
    protected $primaryKey = 'VHqId ';
    public $timestamps = false;
    protected $fillable = [
        'VHqId ',
        'Vertical',
        'HqId',
        'RegionId',
        'DeptId',
        'CompanyId',
        'Status',
    ];
}
