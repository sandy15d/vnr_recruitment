<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_user_permission extends Model
{
    use HasFactory;
    protected $table = 'user_permission';
    protected $primaryKey = 'Id';
    public $timestamps = false;

   
}
