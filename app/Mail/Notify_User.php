<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notify_User extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $account;
    protected $system;
    public function __construct(string $account, string $system)
    {
        $this->account=$account;
        $this->system=$system;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userArray = [
            'account' => $this->account,
            'system' =>$this->system
        ];
        return $this->markdown('emails.notify_user')->with(['users' => $userArray,'system'=>$system]);
    }
}
