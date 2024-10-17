<?php 
namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => Role::ROLE_ADMIN]);
        Role::create(['name' => Role::ROLE_NORMAL_ADMIN]);
        Role::create(['name' => Role::ROLE_USER]);
    }
}
