<?php 
namespace Modules\Core\Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\User\LoginPin;
use Modules\User\Database\factories\UserFactory;

/**
 * Class UsersTableSeeder
 * @package Modules\Core\Database\Seeds
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = $this->createAdminAccount()
            ->assignRole(Role::ROLE_ADMIN);
        $this->makeSuperAdmin($admin);

        $shafaqat = $this->createUserOne()
            ->assignRole(Role::ROLE_ADMIN)
            ->assignRole(Role::ROLE_NORMAL_ADMIN);

    }

    /**
     * @return User
     */
    protected function createAdminAccount(): User
    {
        return User::create([
            'first_name' => 'Usman',
            'last_name' => 'Ali',
            'email' => 'muhammad_usman@usa.edu.pk',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'phone_number' => '+11111111111',
        ]);
    }

    /**
     * Make user a super-admin
     *
     * @param User $user
     * @return bool
     */
    protected function makeSuperAdmin($user)
    {
        $user->is_superadmin = true;

        return $user->save();
    }

    /**
     * Create a new verified user
     *
     * @return User
     */
    protected function createUserOne()
    {
        return User::create([
            'first_name' => 'Shafaqat',
            'last_name' => 'Ali',
            'email' => 'shafaqat.ali@usa.edu.pk',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'phone_number' => '+923070118397',
        ]);
    }

    /**
     * Create an unlimited login pin for the given user
     *
     * @param User $user
     * @return LoginPin
     */
    protected function createUnlimitedLoginPin($user)
    {
        $loginPin = LoginPin::generate(
            $user->phone_number
        );

        $loginPin->expires_at = now()->addDecade();
        $loginPin->pin = 1111;
        $loginPin->save();

        return $loginPin;
    }

    /**
     * Create some dummy users
     *
     * @return Collection|User[]
     */
    protected function createUsers()
    {
        return UserFactory::new()->count(15)->create([
            'email_verified_at' => now()
        ]);
    }
}
