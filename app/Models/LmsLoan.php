<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsLoan extends Model
{
    use HasFactory;

    protected $table = 'lms_loan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'loan_type',
        'amount',
        'duration_month',
        'interest_rate',
        'next_payment',
        'created_date',
        'updated_date',
        'created_timestamp',
        'updated_timestamp',
        'is_show_flag',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(LmsUser::class);
    }
}
