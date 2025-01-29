<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsBankDetails extends Model
{
    use HasFactory;

    protected $table = 'lms_bank_details';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'ifsc_code'    
    ];
    const CREATED_AT = 'created_timestamp';
    const UPDATED_AT = 'updated_timestamp';  

    public function user()
    {
        return $this->belongsTo(LmsUser::class);
    }
}
