<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_task_individual_involved extends Model
{
    use HasFactory;
    protected $table = 'project_task_individual_involved';
    protected $fillable = [
        'project_task_id', 
        'users_id', 
        'due_date',
        'urgency',  
        'created_at'
    ];
}
