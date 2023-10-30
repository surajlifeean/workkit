<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $table = 'travels';

    protected $fillable = [
        'company_id','employee_id','start_date','end_date','visit_purpose','visit_place','travel_mode',
        'arrangement_type_id','expected_budget','actual_budget','description','status', 'attachment' , 'expense_category_id'
    ];

    protected $casts = [
        'company_id'        => 'integer',
        'employee_id'        => 'integer',
        // 'arrangement_type_id'=>'integer',
        'expected_budget'    => 'double',
        'actual_budget'      => 'double',
    ];

    public function expenseCategory()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }
    
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function arrangement_type()
    {
        return $this->hasOne('App\Models\ArrangementType', 'id', 'arrangement_type_id');
    }

}
