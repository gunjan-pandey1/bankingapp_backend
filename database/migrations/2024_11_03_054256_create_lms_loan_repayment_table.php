<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsLoanRepaymentTable extends Migration
{
    public function up()
    {
        Schema::create('lms_loan_repayment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('lms_loan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->integer('total_loan_amount', 15, 2);
            $table->integer('emi_amount_due', 15, 2);
            $table->integer('amount_paid', 15, 2);
            $table->integer('remaining_amount', 15, 2);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('is_show_flag')->default(1);
            $table->dateTime('created_timestamp');
            $table->dateTime('updated_timestamp');

            $table->index(['amount_paid', 'remaining_amount']);
            $table->index(['emi_amount_due']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_loan_repayment');
    }
}

