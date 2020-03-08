<?php

namespace Tests\Unit;

use App\Jobs\SendEmail;
use Tests\TestCase;
use Exception;

class SendEmailTest extends TestCase
{
    /**
     *  Send an email
     *  with valid recipient's email,
     *  subject and message
     *
     * @test
     */
    public function send_an_email_with_valid_data()
    {
        /*
         * Prepare email's data to send
         */
        $data = [
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph
        ];

        $this->json('Post', route('send.email'), $data, [
            'Content-Type' => 'application/json'
            ])->assertStatus(200);

        /*
         *  Make sure the the record
         *  gets saved to the database
         */
        $this->assertDatabaseHas('sent_emails',$data);

    }

}
