<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class screen2ndround extends Model
{
    use HasFactory;
    protected $table = 'screen2ndround';
    protected $primaryKey = 'SScId';
    public $timestamps = false;
    protected $fillable = [
        'SScId',
        'ScId',
        'InterviewMode2',
        'IntervDt2',
        'IntervTime2',
        'IntervLoc2',
        'IntervPanel2',
        'IntervStatus2',
        'SendInterMail2',
        'PanelMail2',
        'IntervLink2',
        'travelEligibility2','CreatedTime','CreatedBy'
    ];
}
