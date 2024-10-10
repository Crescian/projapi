<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies'; // Specify the table name
    protected $fillable = [
        'bu_id', 
        'company_title'
    ];
}
