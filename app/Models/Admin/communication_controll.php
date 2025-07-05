<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class communication_controll extends Model
{
    use HasFactory;
    protected $table = 'communication_control';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'topic', 'sender', 'receiver', 'is_active'];
    public $timestamps = false;
}
