<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionCode extends Model
{
    use HasFactory;
    protected $table = 'master_position_code';
    public $timestamps = false;
    protected $primaryKey = 'position_code_id';
}
