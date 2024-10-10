<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BUcompany extends Model
{
    use HasFactory;
    protected $table = 'bu_companies'; // Specify the table name
    
    protected $fillable = [
        'bu_title'
    ];
}
