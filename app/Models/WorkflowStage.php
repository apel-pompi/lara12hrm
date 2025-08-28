<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'stagename',
        'stage',
    ];
}
