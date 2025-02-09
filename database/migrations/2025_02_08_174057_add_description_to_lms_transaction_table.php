<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToLmsTransactionTable extends Migration
{
    public function up()
    {
        Schema::table('lms_transaction', function (Blueprint $table) {
            $table->string('description')->nullable(); // Add the description column
            $table->date('txnDate')->nullable(); // Add the txnDate column
            
        });
    }

    public function down()
    {
        Schema::table('lms_transaction', function (Blueprint $table) {
            $table->dropColumn('description'); // Remove the description column if the migration is rolled back
            $table->dropColumn('txnDate'); // Remove the txnDate column if the migration is rolled back
        });
    }
}