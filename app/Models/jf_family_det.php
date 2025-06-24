<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jf_family_det extends Model
{
    use HasFactory;
    protected $table  = 'jf_family_det';
    public $timestamps = false;
    protected $fillable = [
        'JCId',
    ];
}
