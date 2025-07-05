<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateActivity extends Model
{
    use HasFactory;
    protected $table = 'candidate_log';
    public $timestamps = false;
    protected $fillable = [
        'JCId','Aadhaar', 'Date', 'Description'
    ];
}