<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'am', 'teknisi'];

        foreach ($roles as $role) {
            User::create([
                'name' => ucfirst($role),
                'username' => $role,
                'password' => Hash::make($role),
                'role' => $role,
                'is_deleted' => false,
            ]);
        }
    }
}
