<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resumesource_master extends Model
{
    use HasFactory;
    protected $table = 'master_resumesource';
    protected $primaryKey = 'ResumeSouId';
    public $timestamps = false;
    protected $fillable = [
        'ResumeSource',
        'Editable',
        'Status'
    ];
}
