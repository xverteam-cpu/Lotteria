<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminEmail = 'admin@local.test';
        $adminUsername = 'admin';
        $password = 'Pa$$w0rd!';

        // Try to find an existing user by email or username
        $user = User::where('email', $adminEmail)
            ->orWhere('username', $adminUsername)
            ->first();

        if ($user) {
            $user->name = 'Local Admin';
            $user->username = $adminUsername;
            $user->email = $adminEmail;
            $user->password = Hash::make($password);
            $user->is_admin = true;
            $user->save();
            return;
        }

        // Pick a unique username if 'admin' is taken
        $username = $adminUsername;
        $i = 0;
        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $adminUsername . $i;
        }

        User::create([
            'name' => 'Local Admin',
            'username' => $username,
            'email' => $adminEmail,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);
    }
}
