<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class screening extends Model
{
    use HasFactory;
    protected $table = 'screening';
    protected $primaryKey = 'ScId';
    public $timestamps = false;
    protected $fillable = [
        'ScId',
        'JAId',
        

    ];
}
