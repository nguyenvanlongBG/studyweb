<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_question_smalls', function (Blueprint $table) {
             $table->integer('id', true);
            $table->integer('question_id');
            $table->integer('user_id');
            $table->tinyInteger('role');
            // role không được xem, được xem, được làm
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
        Schema::dropIfExists('user_question_smalls');
    }
};
