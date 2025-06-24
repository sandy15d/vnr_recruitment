<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    use HasFactory;
    protected $table = 'offerletterbasic';
    public $timestamps = false;
    protected $fillable = [
        'JAId', 'Company', 'Department'
    ];
}
