<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsBankDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('lms_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number')->unique();
            $table->string('account_holder_name');
            $table->string('ifsc_code');
            $table->text('branch_name');
            $table->date('created_date');
            $table->date('updated_date');
            $table->dateTime('created_timestamp');
            $table->dateTime('updated_timestamp');
            $table->tinyInteger('is_show_flag')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->index(['account_number']);
            $table->index(['created_date']);
            $table->index(['updated_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_bank_details');
    }
}
