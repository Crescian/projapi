<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'id', 
        'project_id',
        'users_id',
        'notifications',
        'created_at',
        'updated_at',
        'read'
    ];
}
