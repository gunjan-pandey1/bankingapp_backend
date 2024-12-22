<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRememberTokenToLmsUserTable extends Migration
{
    public function up()
    {
        Schema::table('lms_user', function (Blueprint $table) {
            $table->string('remember_token')->nullable()->after('password');
        });
    }

    public function down()
    {
        Schema::table('lms_user', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
}