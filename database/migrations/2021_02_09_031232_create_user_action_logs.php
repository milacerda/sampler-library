<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_action_logs', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->nullable();
            $table->enum('action',['CHECKIN', 'CHECKOUT']);

            $table->foreignId('user_id')->constrained();
            $table->foreignId('book_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_action_log');
    }
}
