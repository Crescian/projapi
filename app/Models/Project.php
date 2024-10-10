<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'project_title',
        'urgency',
        'project_requirements',
        'events',
        'project_next_step',
        'standing_agreements',
        'project_status',
        'due_date'
    ];
}
