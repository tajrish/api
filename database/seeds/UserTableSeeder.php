<?php

use Tezol\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'first_name'  => 'user',
            'last_name' => 'userian',
            'username' => 'user',
            'email' => 'user@user.com',
            'mobile' => '09222222222',
            'password' => bcrypt('123456'),
            'birth_date' => '2015-01-01',
            'status' =>  'verified',
            'avatar' => 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/s150x150/11821820_497727093723031_1798613263_a.jpg',
            'role' => 'user'
        ]);

        $this->command->info('user with email: user@user.com password: 123456');

        factory(Tezol\Models\User::class, (config('tezol.seed_count')*2) )->create()->each(function($user) {
            //$u->posts()->save(factory(App\Post::class)->make());
        });

        User::create([
            'first_name'  => 'admin',
            'last_name' => 'adminian',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'mobile' => '09111111111',
            'password' => bcrypt('123456'),
            'birth_date' => '2015-01-01',
            'status' =>  'verified',
            'avatar' => 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/s150x150/11821820_497727093723031_1798613263_a.jpg',
            'role' => 'admin'
        ]);
        $this->command->info('user with email: admin@admin.com password: 123456');

    }
}
