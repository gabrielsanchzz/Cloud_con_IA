<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = [
        'project_id',
        'requirement_key',
        'type',
        'operator',
        'value',
        'origin',
    ];
}