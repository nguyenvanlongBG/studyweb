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
        Schema::create('posts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
             $table->string('image_preview');
            $table->text('content');
            $table->tinyInteger('type');
            // type latex or file
            $table->integer('user_id');
            $table->boolean('status')->comment('0: Public 1: Private');
            $table->boolean('approve'); 
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
        Schema::dropIfExists('posts');
    }
};