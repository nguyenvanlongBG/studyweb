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
        Schema::create('chooseds', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('choose_question_id');
            $table->foreign('choose_question_id')->references('id')->on('choose_questions');
            $table->integer('question_test_id');
            $table->foreign('question_test_id')->references('id')->on('question_tests');
            $table->integer('exam_id');
            $table->foreign('exam_id')->references('id')->on('exams');
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
        Schema::dropIfExists('chooseds');
    }
};
