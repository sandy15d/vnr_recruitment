<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserDepartmentMap extends Model
{
    protected $table = 'user_department_map';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'department_id'
    ];
}
