<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeDetail extends Model
{
    use HasFactory;
    protected $table = 'theme_customizer';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'UserId',
        'ThemeStyle',
        'SidebarColor',
        
    ];
}
