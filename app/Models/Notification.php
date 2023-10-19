<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'user_id',
        'receiver_role_user_id',
        'receiver_user_id',
        'is_superadmin',
    ];

    protected $dates = ['deleted_at'];

}
