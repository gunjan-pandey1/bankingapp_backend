<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsUserTable extends Migration
{
    public function up()
    {
        Schema::create('lms_user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('mobile_no')->default(0);
            $table->string('password');
            $table->text('pancard_no')->nullable();
            $table->date('created_date')->nullable();
            $table->date('updated_date')->nullable();
            $table->dateTime('created_timestamp')->nullable();
            $table->dateTime('updated_timestamp')->nullable();
            $table->tinyInteger('is_show_flag')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->index(['created_date']);
            $table->index(['updated_date']);
            $table->index(['id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_user');
    }
}
