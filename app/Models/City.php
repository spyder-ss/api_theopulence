<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'status',
        'is_delete',
    ];

    function countryDetails()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    function stateDetails()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}