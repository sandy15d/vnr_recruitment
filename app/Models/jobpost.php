<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobpost extends Model
{
    use HasFactory;
    protected $table = 'jobpost';
    protected $primaryKey = 'JPId';
    public $timestamps = false;
    protected $fillable = [
       'CompanyId',
       'DepartmentId',
    ];
}
