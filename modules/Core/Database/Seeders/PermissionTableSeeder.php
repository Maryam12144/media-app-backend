<?php 
namespace Modules\Core\Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;
use ReflectionClass;
use ReflectionException;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws ReflectionException
     */
    public function run()
    {
        // Permission::truncate();
        $normalAdminRole = Role::query()->where('name',
            Role::ROLE_NORMAL_ADMIN)->first();

        // get the model class names from the model files
        $modelClasses = $this->getModels();
        $actions = ['create', 'update', 'delete', 'view'];
        $models = [
            // add custom model names
            'option',
        ];
        $customPermissions = [
            // add custom permission names
            'access_admin_dashboard',
        ];
        $permissionToAdd = [];

        foreach ($modelClasses as $model) {
            $class = new ReflectionClass($model);

            $models[] = Str::of($class->getShortName())->snake()->lower();
        }

        foreach ($models as $name) {
            foreach ($actions as $action) {
                $permissionToAdd[] = [
                    'name' => $action . '_' . Str::plural($name),
                    'title' => ucwords($action) . ' ' . str_replace('_', ' ', ucwords($name, '_')),
                    'group_name' => $name,
                    'guard_name' => 'api',
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }

        // foreach ($customPermissions as $permission) {
        //     $permissionToAdd[] = [
        //         'name' => $permission,
        //         'guard_name' => 'api',
        //         'created_at' => now()->toDateTimeString(),
        //         'updated_at' => now()->toDateTimeString(),
        //     ];
        // }

        /*
         * Insert the permissions into the database
         */
        $permissions = Permission::insert(
            $permissionToAdd
        );

        /*
         * Remove every permission from normal admin
         */
        $normalAdminRole->permissions()
            ->detach();

        Permission::chunk(50, function ($permissions) use ($normalAdminRole) {
            $normalAdminRole->permissions()
                ->attach($permissions->pluck('id')->toArray());
        });
    }

    /**
     * Get all of the models
     *
     * @return Collection
     */
    function getModels(): Collection
    {
        $appModels = collect([
            User::class
        ]);

        $pluginModels = collect(
            $this->getPluginModelFiles()
        )->map(function ($path) {
            try {
                return $this->getClassFullNameFromFile($path);
            } catch (\Throwable $exception) {
                return null;
            }
        })
            ->filter(function ($class) {
                if (!$class) return false;
                
                try{
                    $reflection = new ReflectionClass($class);
                } catch (\Throwable $exception) {
                    return null;
                }
                

                if ($reflection->isSubclassOf(Model::class)) {
                    return true;
                }

                return false;
            });

        return $appModels->merge(
            $pluginModels
        );
    }

    /**
     * Get the plugin model files
     *
     * @return array
     */
    protected function getPluginModelFiles()
    {
        $pluginFolders = [];

        foreach (glob(base_path('modules/*'), GLOB_ONLYDIR) as $folder) {
            $pluginFolders[] = $folder;
        }

        $models = [];

        foreach ($pluginFolders as $folder) {
            foreach (glob($folder . '/Entities/*.php') as $model) {
                $models[] = $model;
            }
        }

        return $models;
    }

    /**
     * get the full name (name \ namespace) of a class from its file path
     * result example: (string) "I\Am\The\Namespace\Of\This\Class"
     *
     * @param $filePathName
     *
     * @return  string
     */
    public function getClassFullNameFromFile($filePathName)
    {
        return $this->getClassNamespaceFromFile($filePathName) . '\\' . $this->getClassNameFromFile($filePathName);
    }

    /**
     * get the class namespace form file path using token
     *
     * @param $filePathName
     *
     * @return  null|string
     */
    protected function getClassNamespaceFromFile($filePathName)
    {
        $src = file_get_contents($filePathName);

        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }

    /**
     * get the class name form file path using token
     *
     * @param $filePathName
     *
     * @return  mixed
     */
    protected function getClassNameFromFile($filePathName)
    {
        $php_code = file_get_contents($filePathName);

        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING
            ) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }

        return $classes[0];
    }

}
