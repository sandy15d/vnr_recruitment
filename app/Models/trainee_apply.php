<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trainee_apply extends Model
{
    use HasFactory;
    protected $table = 'trainee_apply';
    protected $primaryKey = 'TId';
    public $timestamps = false;
    protected $fillable = [
        'JAId',
        'JCId',
    ];
}
