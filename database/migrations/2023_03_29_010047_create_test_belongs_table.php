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
        Schema::create('test_belongs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('test_id');
            $table->integer('belong_id')->comment('ID Class, ID User, ID Competition, ID Group, ID Mission');
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
        Schema::dropIfExists('test_belongs');
    }
};
