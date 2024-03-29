<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notify_Manager extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $account;
    protected $password;
    public function __construct(string $account, string $password)
    {
        $this->account=$account;
        $this->password=$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.notify');
        $userArray = [
            'account' => $this->account,
            'password' =>$this->password
        ];
        return $this->markdown('emails.notify')->with(['users' => $userArray]);
    }
}
