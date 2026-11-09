<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Requirement;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'needs',
        'monthly_budget',
    ];

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }
}