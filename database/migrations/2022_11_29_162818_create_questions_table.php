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
        Schema::create('questions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('content');
            $table->integer('user_id');
            $table->text('mathML')->nullable();
            $table->integer('subject_id')->nullable();
            $table->tinyInteger('type')->comment('0: Câu hỏi thảo luận 1: Điền đáp án, 2: Trắc nghiệm, 3: Tự luận, 4: Câu hỏi nhỏ, 5: Câu hỏi lớn ảnh, 6: Câu hỏi lớn chữ');
            // if question test dependence =1 else =0
            $table->text('note')->nullable();
            $table->tinyInteger('scope')->comment('0: Public ra Forum, 1: Private')->default(0);
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
        Schema::dropIfExists('questions');
    }
};
