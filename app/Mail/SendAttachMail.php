<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAttachMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $mail_from;
    protected $mail_subject;
    protected $data;
    protected $view_template;
    protected $attach;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from,$subject,$data,$view='emails.statusrequest_mail',$attach)
    {
        $this->mail_from=$from;
        $this->mail_subject=$subject;
        $this->data=$data;
        $this->view_template=$view;
        $this->attach=$attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view_template)
            ->from($this->mail_from,'ENYO TAMS')
            ->subject($this->mail_subject)
            ->with(['data'=>$this->data])
             ->attachData($this->attach->stream(),'report.pdf', [
                    'mime' => 'application/pdf',
               ]);
    }
}
