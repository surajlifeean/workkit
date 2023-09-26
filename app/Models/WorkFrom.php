<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFrom extends Model
{
    use HasFactory;

    protected $table = 'work_from';

    protected $fillable = [
       'work_from_home_date', 'employee_id', 'company_id'
    ]; 

    protected $dates = ['deleted_at'];
}
