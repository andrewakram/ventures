<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id','sub_category_id',
        'user_id','status',
        'amount','due_on',
        'vat','is_vat_inclusive',
    ];


    public function getStatusAttribute($value)
    {
        return isset($value) ? $value : "";
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id')
            ->select('id','name');
    }
    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'sub_category_id')
            ->select('id','name');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id')
            ->select('name','email');
    }
    public function payments(){
        return $this->hasMany(Payment::class,'transaction_id')
            ->select('id','transaction_id','amount','details','created_at','updated_at');
    }
}
