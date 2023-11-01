<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'admin',
            'email'=>'admin@admin.com',
            'password'=>Hash::make('123456'),
            'user_type'=>'2',
            'is_active'=>'1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        $this->call([
            MasterControlSeeder::class,
        ]);
    }
}
