<?php

namespace App\Models\TestModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectMaster extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'subject_master';
}
