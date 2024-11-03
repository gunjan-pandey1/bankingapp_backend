<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('lms_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained('lms_loan')->onDelete('cascade');
            $table->string('transaction_type');// ['credit', 'debit']);
            $table->integer('transaction_amount', 15, 2);
            $table->date('transaction_date');
            $table->string('razorpay_id')->nullable();
            $table->string('payment_mode');
            $table->date('end_at')->nullable();
            $table->dateTime('created_timestamp');
            $table->dateTime('updated_timestamp');
            $table->tinyInteger('is_show_flag')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->index(['transaction_type', 'transaction_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_transaction');
    }
}

