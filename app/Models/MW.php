<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MW extends Model
{


    protected $table = 'minimum_wage_master';
    protected $primaryKey = 'BWId';
    public $timestamps = false;
    protected $fillable = [
        'BWId',
        'BWageId',
        'YearId',
        'CompanyId',
        'Category',
        'PerDayApr',
        'PerMonthApr',
        'PerDayOct',
        'PerMonthOct',
        'BWageStatus',
        'CrBy',
        'CrDate',
        'UpdBy',
        'UpdDate'

    ];

}
