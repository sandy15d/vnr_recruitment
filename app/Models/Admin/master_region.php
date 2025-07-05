<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_region extends Model
{
    use HasFactory;

    protected $table = 'master_region';
    protected $primaryKey = 'RegionId';
    public $timestamps = false;
    protected $fillable = [
        'ZoneId',
        'RegionName',
        'RegionId',
        'Status',
    ];
}
