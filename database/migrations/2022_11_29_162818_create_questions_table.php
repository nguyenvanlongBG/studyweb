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
            $table->text('latex')->nullable();
            $table->integer('subject_id')->nullable();
            $table->tinyInteger('type')->comment('0: Câu hỏi bình thường, 1: Điền đáp án, 2: Trắc nghiệm, 3: Tự luận');
            // if question test dependence =1 else =0
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
