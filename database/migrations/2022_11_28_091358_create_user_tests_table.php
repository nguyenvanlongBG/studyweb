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
        Schema::create('user_tests', function (Blueprint $table) {
            // $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('test_id');
            $table->tinyInteger('role');
            // role=0 không có quyền, 1 xem, 2 xem và chỉnh sửa
            $table->primary(array('user_id', 'test_id'));
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
        Schema::dropIfExists('user_tests');
    }
};
