<?php

namespace Tests\Unit;

use App\Events\NewUserRegistered;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     *  Provided the valid data
     *  User can register
     *
     * @test
     */
     public function user_can_register()
    {
        /*
         *  Generate a fake password
         */
        $password = $this->faker->password;

        /*
         *  Generate data for a new user
         */
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        /*
        * Create a new user
        */
        $this->post(
            route('user.register'), $data)
            ->assertStatus(201);

    }

}
