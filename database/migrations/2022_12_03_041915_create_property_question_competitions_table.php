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
        // When question has special property
        Schema::create('property_question_competitions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('property_question_id')->comment('id property of question competition');
            $table->string('suggestion')->comment('only big or small question has it');
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
        Schema::dropIfExists('property_question_competitions');
    }
};
