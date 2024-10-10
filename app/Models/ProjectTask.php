<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 
        'urgency', 
        'task_title', 
        'created_at',
        'due_date',
        'project_task_status',
        'remarks'
    ];
}
