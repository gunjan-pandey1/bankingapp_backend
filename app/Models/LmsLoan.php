<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsLoan extends Model
{
    use HasFactory;

    protected $table = 'lms_loan';

    protected $fillable = [
        // Add your fillable attributes here
    ];
}
