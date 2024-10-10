<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_companies extends Model
{
    use HasFactory;
    protected $table = 'project_companies'; // Make sure this matches your table name
    protected $fillable = [
        'project_id',
        'company_id'
        // Add other attributes that should be mass assignable
    ];
}
