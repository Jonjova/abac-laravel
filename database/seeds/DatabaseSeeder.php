<?php

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
        $this->call(PermissionsSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(ManagerSeeder::class);
        $this->call(EditorSeeder::class);
    }
}
