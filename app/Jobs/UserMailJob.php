<?php

namespace App\Jobs;

use App\Mail\UserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
class UserMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $data;
    public function __construct($data)
    {
        $this->queue = 'email';
        $this->data = $data;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $items_limit = 1;
        $delay = 0;
        $data_to_process = [];
        $data_to_dispatch = [];

        if ($this->attempts() > 1) {
            \Log::error('the email was not send ' . json_encode($this->data));
            return false;
        }
        if (count($this->data) > $items_limit) {
            $data_to_process = array_slice($this->data, 0, $items_limit);
            $data_to_dispatch = array_slice($this->data, $items_limit);
            Queue::later($delay, new UserMailJob($data_to_dispatch));
        } else {
            $data_to_process = $this->data;
        }

        foreach($data_to_process as $item) {
            $email = filter_var($item['email'], FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                continue;
            }

            $data = [
                'subject' => $item['subject'],

            ];

            Mail::send([], $data, function ($message) use ($item) {
                $message->to($item['email'], $item['title'])
                    ->subject(replace_newlines_with_space($item['subject']))
                    ->from('test@mail.com', 'Mail Job')
                    ->setBody(replace_newlines_with_space($item['subject']), 'text/html');
            });
            Mail::to($item['email'])->send(new \App\Mail\UserMail($item));
            \Log::info('message');
            app('mailer')->getSwiftMailer()->getTransport()->stop();
        }
    }
}
