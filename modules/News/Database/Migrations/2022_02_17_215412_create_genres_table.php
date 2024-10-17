<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('news.table_names');

        Schema::create($tableNames['genres'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('is_default')->nullable()->default('0');
            $table->Integer('sort_order')->nullable()->default('9999');
            $table->string('lang')->default('en');
            $table->unsignedBigInteger('added_by')->default('1');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('added_by')->references('id')->on($tableNames['users']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('news.table_names');
        Schema::dropIfExists($tableNames['genres']);
    }
}
