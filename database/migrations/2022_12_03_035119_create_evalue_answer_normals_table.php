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
        Schema::create('evalue_answer_normals', function (Blueprint $table) {
             $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('answer_normal_id');
            $table->integer('evalue');
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
        Schema::dropIfExists('evalue_answer_normals');
    }
};
