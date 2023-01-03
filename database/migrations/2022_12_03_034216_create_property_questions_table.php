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
        Schema::create('property_questions', function (Blueprint $table) {
            $table->integer('id', true);
            // $table->tinyInteger('type')->comment('0: Normal , 1: Big, 2: Small');
            $table->integer('question_id');
            $table->integer('point')->default(0);
            $table->integer('page');
            $table->integer('index');
            $table->integer('dependence_id')->comment('Id Test or Id Question Big');
            $table->integer('result_id')->default(-1)->nullable();
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
        Schema::dropIfExists('property_questions');
    }
};
