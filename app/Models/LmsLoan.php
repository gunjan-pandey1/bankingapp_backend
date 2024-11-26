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
        // Add your fillable attributes here
    ];
}
