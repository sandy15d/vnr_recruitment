<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobcandidate extends Model
{
    use HasFactory;

    protected $table = 'jobcandidates';
    protected $primaryKey = 'JCId';
    public $timestamps = false;
    protected $fillable = [
        'JCId',
        'ReferenceNo',
        'Title',
        'FName',
        'LName',
        'MName',
        'Gender',
        'FatherTitle',
        'FatherName',
        'Email',
        'Phone',
        'Nationality',
        'for_test',
        'TotalYear',
        'TotalMonth'

    ];
}
