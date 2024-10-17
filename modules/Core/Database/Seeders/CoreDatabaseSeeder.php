<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Contract\Database\Seeders\ContractTableSeeder;
use Modules\Notification\Database\Seeders\NotificationTableSeeder;
use Modules\Quality\Database\Seeders\QualityTableSeeder;

/**
 * Class CoreDatabaseSeeder
 *
 * @package Modules\Core\Database\Seeders
 */
class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UsersTableSeeder::class);

    }
}
