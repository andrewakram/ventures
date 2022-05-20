<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin =[
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'type' => 'admin',
        ];
        User::updateOrCreate($admin);


        $data = [
            [
                'name' => 'user1',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'user3',
                'email' => 'user3@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];
        foreach ($data as $get) {
            $user = User::updateOrCreate($get);
            $user->createToken('LaravelSanctumAuth')->plainTextToken;

        }
    }
}
