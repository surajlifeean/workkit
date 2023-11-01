<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title'
    ];

    public function travel()
    {
        return $this->belongsTo(Travel::class, 'expense_category_id');
    }

    public function expense()
    {
        return $this->belongsTo('App\Models\Expense', 'expense_category_id');
    }
}
