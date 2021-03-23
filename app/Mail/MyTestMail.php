<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyTestMail extends Mailable
{
    use Queueable, SerializesModels;

//    public $params;
//
//    public function __construct($params)
//    {
//        $this->params = $params;
//    }




    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('view.name');
//        return $this->view('member.activate')
//            ->subject('確認您的帳戶')
//            ->with([
//                'link' => sprintf(
//                    '%s%s',
//                   route('api.member.verify')
//                ),
//            ]);

        return $this->from('example@example.com')->view('member.activate');
//        return $this->subject("警告訊息")
//            ->view('member.activate')
//            ->subject('確認您的帳戶')
//            ->with([
//                'link' => $this->params,
//            ]);


    }
}
