<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        factory(User::class, 10)->create();
        factory(User::class)->create([
            'email' => 'test@sampler.com'
        ]);
    }
}
