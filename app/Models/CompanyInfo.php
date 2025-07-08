<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'companyname',
        'address_one',
        'address_two', 
        'company_phone',
        'company_email',
        'companylogo'
    ];
    
}
