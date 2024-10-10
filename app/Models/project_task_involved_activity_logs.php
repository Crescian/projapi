<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_task_involved_activity_logs extends Model
{
    use HasFactory;
    protected $table = 'project_task_involved_activity_logs';

    protected $fillable = [
        'project_task_id', 
        'users_id', 
        'project_activity'
    ];
}
