<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBookActivity extends Model
{
    use HasFactory;
    protected $table = 'logbook';
    protected $fillable = [
        'subject','type', 'url', 'method', 'ip', 'agent', 'user_id'
    ];
}
