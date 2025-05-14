<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_category_id',
        'account_id',
        'warehouse_id',
        'amount',
        'date',
        'reference',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
