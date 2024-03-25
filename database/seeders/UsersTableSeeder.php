<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'status' => 'active',
                'password' => Hash::make('#@1Password'),
                'role' => 'Admin',
                'lastlogin' => now(),
            ],

            // Add additional users as needed
        ];

        $existingUsers = [];
        $createdUsers = [];

        foreach ($users as $userData) {
            // Remove 'role' from the array before creating the user
            $roleName = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($user->wasRecentlyCreated) {
                $createdUsers[] = $user->email;

                // Check if the role exists and assign it to the user
                if (Role::where('name', $roleName)->exists()) {
                    $user->assignRole($roleName);
                }
            } else {
                $existingUsers[] = $user->email;
            }
        }

        // Output to the console
        $this->command->info('Existing Users: ' . implode(', ', $existingUsers));
        $this->command->info('Created Users: ' . implode(', ', $createdUsers));
    }
}