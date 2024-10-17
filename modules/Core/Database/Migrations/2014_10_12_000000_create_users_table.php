<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('phone_number')
                ->nullable();
            $table->string('email')
                ->nullable();

            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('full_name', 50);

            $table->string('password');


            $table->string('avatar', 128)->nullable();

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->string('lang', 5)
                ->default(User::USER_LANGUAGE_ENGLISH);

            $table->boolean('is_superadmin')
                ->default(false);

            $table->boolean('suspended')
                ->default(false);

            $table->date('birthday')
                ->nullable();

            $table->string('country')
                ->nullable();
            $table->string('state')
                ->nullable();
            $table->string('city')
                ->nullable();

            $table->string('status')->default(User::STATUS_ACTIVE)->nullable();
            $table->dateTime('status_updated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'phone_number', 'deleted_at',
            ]);

            $table->unique([
                'email', 'deleted_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
