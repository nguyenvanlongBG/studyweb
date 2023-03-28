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
        Schema::create('answer_tests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('answer')->comment('choice question it is ID Choose, Fill question it is value, Essay question it is value')->nullable();
            $table->integer('question_id');
            $table->integer('exam_id');
            $table->float('point')->nullable()->default(null);
            $table->unique(array('question_id', 'exam_id'));
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
        Schema::dropIfExists('answer_tests');
    }
};
