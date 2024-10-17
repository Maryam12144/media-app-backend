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
        Schema::create('evaluate_tickers', function (Blueprint $table) {
            $table->id();
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('evaluator_id');
            $table->unsignedBigInteger('poster_id');
            $table->unsignedBigInteger('ticker_id');
            $table->foreign('evaluator_id')->references('id')->on('users');
            $table->foreign('poster_id')->references('id')->on('users');
            $table->foreign('ticker_id')->references('id')->on('tickers');
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
        Schema::dropIfExists('evaluate_tickers');
    }
};
