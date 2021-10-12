<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Interfaces\EmailInterface\Email as EmailInterface;
//use Mailjet\Request;
use Exception;
use Illuminate\Support\Arr;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    protected $emailService;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        /*
         * get the default email service, and use that
         */
        $this->emailService = app(config('mail.service'));

        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->emailService->send($this->data);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        \Log::info('Something went wrong. Executing fallback service');
        
        /*
         * the default email service
         * not able to send an email
         * switch to any random fallback service
         * and try dispatch this job again
         */
        $fallback = Arr::random(config('mail.fallbacks'));

        config(['mail.service' => $fallback ]);

        self::dispatch($this->data);
    }
}
