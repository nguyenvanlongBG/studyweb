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
        Schema::create('question_tests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('content');
            $table->float('point');
            $table->integer('test_id');
            $table->foreign('test_id')->references('id')->on('tests');
            // $table->integer('result_id');
            // $table->foreign('result_id')->references('id')->on('choose_questions');
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
        Schema::dropIfExists('question_tests');
    }
};
