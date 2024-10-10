<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_logs extends Model
{
    use HasFactory;
    protected $table = 'project_logs'; // Specify the table name
    protected $fillable = [
        'project_id', 
        'events',
    ];
}
