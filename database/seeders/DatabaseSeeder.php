<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \Modules\Core\Database\Seeders\RoleTableSeeder::class,
            \Modules\Core\Database\Seeders\PermissionTableSeeder::class,
            \Modules\Core\Database\Seeders\UsersTableSeeder::class,
        ]);
    }
}

