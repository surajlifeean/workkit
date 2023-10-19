<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivePlan extends Model
{
    use HasFactory;

    protected $table = 'active_plans';

    protected $fillable = ['subs_plan_id', 'total_users', 'status', 'plan_request_id', 'start_date', 'end_date'];
}
