<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNewUserRegistrationResponse()
    {
        $user = factory(User::class)->make();
        
        $response = $this->post('/api/users', [
            'password' => 'password',
            'name' => $user->name,
            'email' => $user->email,
            'date_of_birth' => $user->date_of_birth
        ]);

        $response->assertStatus(201);
    }

    public function testUniqueUserRegistrationResponse()
    {
        $user = factory(User::class)->create();
        
        $response = $this->post('/api/users', [
            'password' => 'password',
            'name' => $user->name,
            'email' => $user->email,
            'date_of_birth' => $user->date_of_birth
        ]);

        $response->assertStatus(422);
    }

    public function testDateBirthRegistrationResponse()
    {
        $user = factory(User::class)->make();
        
        $response = $this->post('/api/users', [
            'password' => 'password',
            'name' => $user->name,
            'email' => $user->email,
            'date_of_birth' => date('Y-m-d')
        ]);

        $response->assertStatus(422);
    }

}
