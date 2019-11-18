<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnswserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('answer_id');
            $table->unsignedBigInteger('user_id');
            $table->text('body')->nullable();
            $table->string('command')->default('')->nullable();
            $table->string('answers')->default('')->nullable();
            $table->boolean('need_send_to_email')->default(0)->nullable();
            $table->boolean('was_sent_to_email')->default(0)->nullable();
            $table->string('type')->default('text')->nullable();
            $table->string('filepath')->nullable();
            $table->string('filename')->nullable();
            $table->string('caption')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('answers');
    }
}
