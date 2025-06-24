<?php

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_elg extends Model
{
    use HasFactory;
    protected $table = 'master_eligibility';
    protected $primaryKey = 'EligMasId';
    public $timestamps = false;
    
   
}
