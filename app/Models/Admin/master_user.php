<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_user extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'Username',
        'email',
        'role',
        'Contact',
        'password',
        'Status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('users', function (Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
    }
}
