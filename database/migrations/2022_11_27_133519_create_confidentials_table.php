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
        Schema::create('confidentials', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->text('content');
            $table->text('approve');
            $table->boolean('incognito');
            // incognito=0 display avatar incognito=1 hidden avatar

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
        Schema::dropIfExists('confidentials');
    }
};
