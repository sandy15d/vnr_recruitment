<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_country extends Model
{
    use HasFactory;
    protected $table = 'core_country';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'country_name',
        'country_code',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('core_country', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }
}
