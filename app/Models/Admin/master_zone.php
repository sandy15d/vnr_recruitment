<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_zone extends Model
{
    use HasFactory;
    protected $table = 'master_zone';
    protected $primaryKey = 'ZoneId';
    public $timestamps = false;
    protected $fillable = [
        'ZoneId',
        'ZoneName',

    ];


}
