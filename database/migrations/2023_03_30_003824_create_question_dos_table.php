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
        Schema::create('question_dos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('type')->comment('Belong Test or Big Question');
            $table->integer('question_id');
            $table->integer('belong_id');
            $table->float('point')->default(1);
            $table->integer('index')->default(0);
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
        Schema::dropIfExists('question_dos');
    }
};
