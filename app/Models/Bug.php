<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'severity',
        'priority',
        'status',
        'steps_to_reproduce',
        'environment_info',
        'attachments',
        'user_id',
        'assigned_to', 
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
