<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provide recipient email, subject and message to send an email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $to = $this->ask('What is the recipient\'s email?');
        $subject = $this->ask('What is the email subject?');
        $message = $this->ask('What is the email message?');

        $validator = Validator::make([
            'to' => $to,
            'subject' => $subject,
            'message' => $message
        ], [
            'to' => ['required'],
            'subject' => ['required','max:255'],
            'message' => ['required', 'max:255']
        ]);

        if ($validator->fails()) {
            $this->info('Failed to send email. Please make sure to provide correct necessary information.');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $this->info('Sit tight, email is on its way...');

        $data = array(
            'to' => $to,
            'subject' => $subject,
            'message' => $message
        );

        /*
         * send an email
         * with queue job
         */
        \App\Jobs\SendEmail::dispatch($data)
            ->onQueue('email');

        $this->info('Email successfully sent to ' . $to . ' on ' . Carbon::now());
    }
}
