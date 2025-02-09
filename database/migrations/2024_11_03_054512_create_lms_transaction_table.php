<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLmsTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('lms_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained('lms_loan')->onDelete('cascade');
            $table->string('transaction_type')->default('debit'); // ['credit', 'debit']);
            $table->decimal('transaction_amount', 15, 2);
            $table->string('razorpay_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->date('end_at')->nullable();
            $table->dateTime('created_timestamp')->nullable()->useCurrent();
            $table->dateTime('updated_timestamp')->nullable();
            $table->tinyInteger('is_show_flag')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->index(['transaction_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_transaction');
    }
}
