<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'kevin',
            'email' => 'kebryansg@gmail.com',
            'password' => password_hash('kebryansg', PASSWORD_BCRYPT)
        ]);
        factory(App\User::class)->create([
            'name' => 'ronald',
            'email' => 'ronald@gmail.com',
            'password' => password_hash('ronald', PASSWORD_BCRYPT)
        ]);
    }
}
