<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsUser extends Model
{
    use HasFactory;

    protected $table = 'lms_user';

    protected $fillable = [
        // Add your fillable attributes here
    ];
}
