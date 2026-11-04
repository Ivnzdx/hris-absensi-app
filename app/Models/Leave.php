<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    // Tambahkan relasi ini agar model Leave mengenali relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}