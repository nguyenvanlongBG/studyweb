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
            $table->tinyInteger('type');
             // type=0 exam, type=1 mission, type=2 competition
            $table->integer('belong_id')->nullable();
           // Bài kiểm tra trong lớp hay thuộc về mission
            $table->boolean('scope');
            //scope=0 public, scope=1 private in system
             $table->integer('fee');
             $table->integer('candidates');
             $table->integer('reward_init');
            $table->string('note');
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
