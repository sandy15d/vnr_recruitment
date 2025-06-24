<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobapply extends Model
{
    use HasFactory;
    protected $table = 'jobapply';
    protected $primaryKey = 'JAId';
    public $timestamps = false;
    protected $fillable = [
        'JAId',
        'JCId',
    ];
}
