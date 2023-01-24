<?php

namespace App\Console\Commands;

use App\Mail\UserMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoUserWelcome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:userwelcome';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::get();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                $data_to_email[] = [
                    'email' => $user->email,
                    'subject' => 'Welcome to our system',
                    'title' => 'Welcome',
                ];
                dispatch(new \App\Jobs\UserMailJob($data_to_email));
            }
        }

        return 0;
    }
}
