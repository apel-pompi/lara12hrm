<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branchname',
        'description',
        'person',
        'designation',
        'address',
        'email',
        'phone',
        'active',
    ];
}
