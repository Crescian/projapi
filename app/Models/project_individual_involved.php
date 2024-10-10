<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_individual_involved extends Model
{
    use HasFactory;
    protected $table = 'project_individual_involved';
    protected $fillable = [
        'project_id',
        'users_id',
        'project_role'
    ];
}
