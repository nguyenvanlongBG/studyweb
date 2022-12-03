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
        // Câu trả lời bình thường là câu trả lời gửi đi cho mọi người xem không tính điểm
        Schema::create('answer_normals', function (Blueprint $table) {
             $table->integer('id', true);
            $table->integer('question_id');
            $table->integer('user_id');
            $table->text('content');
            $table->integer('fee');
            $table->float('evaluate');
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
        Schema::dropIfExists('answer_normals');
    }
};
