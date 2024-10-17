<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $tableNames = config('news.table_names');

        Schema::create($tableNames['news'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_type_id');
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('geographical_criteria_id');
            $table->tinyInteger('is_celebrity')->default(false);
            $table->unsignedBigInteger('celebrity_genre_id')->nullable();
            $table->string('celebrity_name')->nullable();
            $table->unsignedBigInteger('prominence_id');
            $table->tinyInteger('is_controversy')->default(false);
            $table->unsignedBigInteger('human_interest_id');
            $table->unsignedBigInteger('news_type_id');
            $table->string('video_name');
            $table->string('video_path');
            $table->longText('ticker_text')->nullable();
            $table->float('ticker_duration')->nullable();
            $table->integer('news_loop_id')->nullable();
            $table->float('news_duration')->nullable();
            $table->integer('loop_sequence');
            $table->float('news_length');
            $table->bigInteger('views')->default(0);
            $table->string('status')->default('pending');
            $table->tinyInteger('is_active')->default('1');
            $table->Integer('sort_order')->nullable()->default('9999');
            

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on($tableNames['users']);
            $table->foreign('video_type_id')->references('id')->on($tableNames['video_types']);
            $table->foreign('genre_id')->references('id')->on($tableNames['genres']);
            $table->foreign('geographical_criteria_id')->references('id')->on($tableNames['geographical_criterias']);
            $table->foreign('celebrity_genre_id')->references('id')->on($tableNames['genres']);
            $table->foreign('prominence_id')->references('id')->on($tableNames['prominences']);
            $table->foreign('human_interest_id')->references('id')->on($tableNames['human_interests']);
            $table->foreign('news_type_id')->references('id')->on($tableNames['news_types']);

        });


        Schema::create($tableNames['news_has_proximities'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('coordinates')->nullable();

            $table->foreign('news_id')
                ->references('id')
                ->on($tableNames['news'])
                ->onDelete('cascade');
        });


        Schema::create($tableNames['news_has_reports'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->unsignedBigInteger('report_type_id');
            $table->tinyInteger('is_ptc')->default(false);
            $table->tinyInteger('is_relevant_footage')->default(false);
            $table->tinyInteger('is_on_spot')->default(false);
            $table->tinyInteger('is_sot')->default(false);
            $table->tinyInteger('is_closing')->default(false);
            $table->tinyInteger('is_header')->default(false);
            $table->tinyInteger('is_name_strip')->default(false);
            $table->tinyInteger('is_duration_60_to_90')->default(false);
            $table->tinyInteger('is_ticker')->default(false);

            $table->foreign('news_id')
                ->references('id')
                ->on($tableNames['news'])
                ->onDelete('cascade');

            $table->foreign('report_type_id')
                ->references('id')
                ->on($tableNames['report_types']);
        });


        Schema::create($tableNames['news_has_packages'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->unsignedBigInteger('package_type_id');
            $table->tinyInteger('is_bumper')->default(false);
            $table->tinyInteger('is_opening_ptc')->default(false);
            $table->tinyInteger('is_relevant_footage')->default(false);
            $table->tinyInteger('is_avo')->default(false);
            $table->tinyInteger('is_diff_version_of_narration')->default(false);
            $table->tinyInteger('is_reporter_own_narrative')->default(false);
            $table->tinyInteger('is_on_camera_bits')->default(false);
            $table->tinyInteger('is_music')->default(false);
            $table->tinyInteger('is_duration_120_to_180')->default(false);
            $table->tinyInteger('is_header')->default(false);
            $table->tinyInteger('is_name_strip')->default(false);
            $table->tinyInteger('is_ticker')->default(false);

            $table->foreign('news_id')
                ->references('id')
                ->on($tableNames['news'])
                ->onDelete('cascade');

            $table->foreign('package_type_id')
                ->references('id')
                ->on($tableNames['package_types']);
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
        Schema::dropIfExists($tableNames['news_has_packages']);
        Schema::dropIfExists($tableNames['news_has_reports']);
        Schema::dropIfExists($tableNames['news_has_proximities']);
        Schema::dropIfExists($tableNames['news']);
    }
}
