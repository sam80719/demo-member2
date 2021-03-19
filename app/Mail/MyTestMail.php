<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyTestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('view.name');
        return $this->view('member.activate')
            ->subject('確認您的帳戶')
            ->with([
                'link' => sprintf(
                    '%s?%s',
                    '127.0.0.1',
                    http_build_query([
                        'url' => config('mail.member_active_url') . $this->checkCode,
                        'email' => $this->email,
                    ])
                ),
            ]);


    }
}
