<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lms_loan', function (Blueprint $table) {
            $table->string('next_payment')->nullable()->after('interest_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_loan', function (Blueprint $table) {
            // $table->string('next_payment')->nullable()->after('interest_rate'); // Add 'status' column
        });
    }
};
