<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'companyname',
        'address_one',
        'address_two', 
        'company_phone',
        'company_email',
        'companylogo',
        'loginimage'
    ];
    
}
