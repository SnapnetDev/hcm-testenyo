<?php

namespace App\Notifications;

use App\Exemption;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ApproveExemptionRequest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $exemption;
    public function __construct(Exemption $exemption)
    {
        //

        $this->exemption=$exemption;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Approve Exemption Request')
            ->line('You are to review and approve a exemption request, '.$this->exemption->type.' applied for by '.$this->exemption->user->name)
            // ->action('View Leave Request', url('/documents/reviews'))
            ->line('Thank you for using our application!');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'subject'=>'Approve Exemption Request-' .$this->exemption->type,
            'details'=>"<ul class=\"list-group list-group-bordered\">
                  <li class=\"list-group-item \"><strong>Employee Name:</strong><span style\"text-align:right\">".$this->exemption->user->name."</span></li>
                  <li class=\"list-group-item  \"><strong>Attendance Date:</strong><span style\"text-align:right\">".$this->exemption->attendance->date."</span></li>
                  <li class=\"list-group-item  \"><strong>Exemption Type:</strong><span style\"text-align:right\">".$this->exemption->type."</span></li>
                  <li class=\"list-group-item \"><strong>Reason:</strong><span style\"text-align:right\">".$this->exemption->reason."</span></li>
                </ul>",
            'message'=>'You are to review and approve an exemption request '.$this->exemption->type.' applied for by '.$this->exemption->user->name,
            // 'action'=>route('documents.showreview', ['id'=>$this->document->id]),
            'type'=>'Exemption Request'
        ]);

    }
}
