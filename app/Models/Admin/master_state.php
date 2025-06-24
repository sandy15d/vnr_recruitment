<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_state extends Model
{
    use HasFactory;
    protected $table = 'core_state';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'state_name',
        'state_code',
        'country_id',
        'is_active',
        
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_state', function (Builder $builder) {
            $builder->orderBy('state_name', 'asc');
        });
    }
}
