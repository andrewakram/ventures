<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id','amount','details'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getDetailsAttribute($value)
    {
        return isset($value) ? $value : "";
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class,'transaction_id')
            ->select('category_id','sub_category_id',
                'user_id','status',
                'amount','due_on',
                'vat','is_vat_inclusive');
    }
}
