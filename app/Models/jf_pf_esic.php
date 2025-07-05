<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jf_pf_esic extends Model
{
    use HasFactory;
    protected $table  = 'jf_pf_esic';
    public $timestamps = false;
    protected $fillable = [
        'JCId',
    ];
}
