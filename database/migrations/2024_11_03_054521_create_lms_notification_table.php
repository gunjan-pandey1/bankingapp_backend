<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsNotificationTable extends Migration
{
    public function up()
    {
        Schema::create('lms_notification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_user')->onDelete('cascade');
            $table->text('message');
            $table->string('notification_type'); // ['info', 'warning', 'error']);
            $table->string('notification_status'); // ['info', 'warning', 'error']);
            $table->dateTime('created_timestamp');
            $table->dateTime('updated_timestamp');
            $table->tinyInteger('is_show')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->index(['notification_status']);
            $table->index(['notification_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_notification');
    }
}
