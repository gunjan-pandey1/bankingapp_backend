<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsTransaction extends Model
{
    use HasFactory;

    protected $table = 'lms_transaction';

    protected $fillable = [
        // Add your fillable attributes here
    ];
}
