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

    /**
     *  Throw an error
     *  when invalid request data is provided
     *
     * @test
     */
    public function throw_an_error_when_invalid_data_is_provided()
    {
        /*
         * Prepare email's data to send
         */
        $data = [
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph
        ];

        /*
         * make the post request invalid
         * by missing any field;
         */
        unset($data['to']);

        $this->json('Post', route('send.email'), $data, [
            'Content-Type' => 'application/json'
        ])->assertStatus(400);


    }

    /**
     * Send an email with the default
     * email service provider
     * with valid recipient's email,
     * subject and message
     *
     * @test
     */
    public function send_an_email_with_default_email_service_provider()
    {
        /*
        * Prepare email's data to send
        */
        $data = [
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph
        ];

        /*
         * Send an email and save the response
         */
        $response = $this->json('Post', route('send.email'), $data, [
            'Content-Type' => 'application/json'
        ]);
        /*
        * The email service used to send this email
        */
        $emailServiceUsedToSendEmail = $response['data']['message']['service'];

        /*
         *  The default email service
         */
        $defaultEmailService = config('mail.service');

        $this->assertEquals($defaultEmailService, $emailServiceUsedToSendEmail);

    }

    /**
     * Send an email with a fallback service
     * if the something went wrong when
     * try to send an email with the default one
     *
     * @test
     */
    public function send_an_email_with_a_fallback_email_service_provider()
    {
        /*
         *  The default email service
         */
        $defaultEmailService = config('mail.service');

        /*
        * Prepare email's data to send
        */
        $data = [
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph
        ];

        /*
         * Fail a job
         */
        $exception = new Exception();
        $sendEmailJob = new SendEmail($data);
        $response = $sendEmailJob->failed($exception);

        /*
         * Send an email and save the response
         */
        $response = $this->json('Post', route('send.email'), $data, [
            'Content-Type' => 'application/json'
        ]);

        /*
        * The email service used to send this email
        */
        $emailServiceUsedToSendEmail = $response['data']['message']['service'];

        $this->assertNotEquals($defaultEmailService, $emailServiceUsedToSendEmail);
    }

}
