<?php

namespace Tests\Unit;

use App\Events\NewUserRegistered;
use Illuminate\Support\Arr;
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

    /**
     * Throw an error if any one the
     * required field is missing
     * @test
     */
    public function user_can_not_register_with_any_missing_field()
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
         * Get a random index number
         * from an array
         */
        $collection = collect($data);

        $keys = $collection->keys();

        $randomKey = rand(1,count($keys)) -1;

        /*
         * Unset any require field
         * basd on the $randomIndex
         */
        Arr::pull($data, $keys[$randomKey]);

        /*
        * Throw an error
        */
        $this->post(
            route('user.register'), $data)
            ->assertStatus(400);
    }

}
