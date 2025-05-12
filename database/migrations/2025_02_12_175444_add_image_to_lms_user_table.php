<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToLmsUserTable extends Migration
{
    public function up()
    {
        Schema::table('lms_user', function (Blueprint $table) {
            $table->string('image')->nullable(); // Add image column
        });
    }

    public function down()
    {
        Schema::table('lms_user', function (Blueprint $table) {
            $table->dropColumn('image'); // Remove image column if migration is rolled back
        });
    }
}