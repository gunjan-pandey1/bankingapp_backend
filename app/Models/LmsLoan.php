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

        'loan_type',
        'amount',
        'interest_rate',
        'duration_month',
        'status',
        'is_show_flag'
    ];
    const CREATED_AT = 'created_timestamp';
    const UPDATED_AT = 'updated_timestamp';  
    public function user()
    {
        return $this->belongsTo(LmsUser::class);
    }
}
