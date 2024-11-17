<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsNotification extends Model
{
    use HasFactory;

    protected $table = 'lms_notification';

    protected $fillable = [
        // Add your fillable attributes here
    ];
}
