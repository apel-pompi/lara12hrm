<?php

namespace App\Models\Student;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InactiveLeadTransferLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_ids',
        'student_count',
        'from_user_id',
        'to_user_id',
        'transferred_by_user_id',
        'period',
        'transfer_type',
        'note',
    ];

    protected $casts = [
        'student_ids' => 'array',
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function transferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by_user_id');
    }
}
