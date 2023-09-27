<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('message')->nullable();
            $table->integer('user_id');
            $table->integer('receiver_role_user_id')->nullable();
            $table->integer('receiver_user_id')->nullable();
            $table->integer('company_id');
            $table->integer('leave_id')->nullable();
            $table->string('is_seen', 1)->default('0');
            $table->timestamps(6);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
