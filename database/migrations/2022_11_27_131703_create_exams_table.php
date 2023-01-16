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
        Schema::create('exams', function (Blueprint $table) {
           $table->integer('id', true);
            $table->float('point')->default(0);
            $table->integer('user_id');
            $table->integer('test_id');
            // 0 Thi thử và 1 thi thật
            $table->boolean('type')->default(true);
            $table->boolean('is_complete')->default(false);
            // is_complete=false đang làm, is_complete =true đã hoàn thành
            $table->boolean('is_marked')->default(false);
            // is_marked=false chưa chấm xong, is_marked =true đã chấm xong
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
        Schema::dropIfExists('exams');
    }
};
