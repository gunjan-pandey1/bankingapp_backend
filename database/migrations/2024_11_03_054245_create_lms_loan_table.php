<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsLoanTable extends Migration
{
    public function up()
    {
        Schema::create('lms_loan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->integer('duration_month');
            $table->decimal('interest_rate', 5, 2);
            $table->date('created_at');
            $table->timestamps();
            $table->tinyInteger('is_show_flag')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_loan');
    }
}

