<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsTransaction extends Model
{
    use HasFactory;

    protected $table = 'lms_transaction';

    public $timestamps = false;

    protected $fillable = [
        // Add your fillable attributes here
        'user_id',
        'loan_id',
        'transaction_type',
        'transaction_amount',
        'created_timestamp',
        'description',
        'txnDate'
    ];

    const CREATED_AT = 'created_timestamp';
    const UPDATED_AT = 'updated_timestamp'; 

    public function loan()
    {
        return $this->belongsTo(LmsLoan::class);
    }
}
