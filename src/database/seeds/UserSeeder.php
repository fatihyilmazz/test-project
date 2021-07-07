<?php

use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       DB::table('users')->delete();

        User::insert([
           'name' => 'Fatih',
           'email' => 'fatih@test.com',
           'password' => Hash::make('123456'),
           'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
