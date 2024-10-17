<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('criteria');
            $table->unsignedBigInteger('evaluator_id');
            $table->unsignedBigInteger('poster_id');
            $table->unsignedBigInteger('news_id');
            $table->foreign('evaluator_id')->references('id')->on('users');
            $table->foreign('poster_id')->references('id')->on('users');
            $table->foreign('news_id')->references('id')->on('news');

            $table->softDeletes();
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
        Schema::dropIfExists('evaluations');
    }
};
