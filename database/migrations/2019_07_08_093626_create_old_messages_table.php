<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bot_id');
            $table->string('chatId');
            $table->text('body')->nullable();;
            $table->string('type')->nullabe();
            $table->string('senderName')->nullable();
            $table->string('author')->nullable();
            $table->unsignedInteger('time')->nullable();
            $table->string('command')->nullable();
            $table->string('type_answer')->default('text')->nullable();
            $table->string('filepath')->nullable();
            $table->string('filename')->nullable();
            $table->string('caption')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('answers')->nullable();
            $table->boolean('need_send_to_email')->default(false)->nullable();
            $table->boolean('was_sent_to_email')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('old_messages');
    }
}
