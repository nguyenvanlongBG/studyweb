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
        Schema::create('tests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->tinyInteger('type')->comment('Exam Mission Competition Exercise in group');
             // type=0 exam, type=1 mission, type=2 competition
            $table->tinyInteger('subject_id');
            // 1 All subject, 2 Math, 3 Chemistry, 4 Phisical
            $table->boolean('scope');
            //scope=0 public, scope=1 private in system
            $table->boolean('allow_rework')->default(true);
            // Cho phép làm nhiều lần
            $table->boolean('mark_option')->default(true);
            // true: Chấm ngay sau khi nộp bài, false: Chấm khi hết thời gian làm bài
             $table->integer('fee');
             $table->integer('candidates')->default(0);
             $table->integer('total_page');
             $table->integer('reward_init');
            $table->string('note')->nullable();
            $table->timestamp('time_start')->useCurrent();
            $table->timestamp('time_finish')->nullable();
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
        Schema::dropIfExists('tests');
    }
};
