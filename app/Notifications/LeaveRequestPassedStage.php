<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveApproval;
use App\Stage;

class LeaveRequestPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $leave_approval;
      public $stage;
      public $nextstage;
    public function __construct(LeaveApproval $leave_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->leave_approval=$leave_approval;
        $this->stage=$stage;
        $this->nextstage=$nextstage;
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
        ->subject('Leave request Passed an Approval Stage')
        ->line('The leave request, '.$this->leave_approval->leave_request->leave->name.' which you submitted for approval in the '.$this->stage->workflow->name.'has been approved at the '.$this->stage->name.' by '.$this->stage->user->name)
        ->line('The leave request  which you submitted for approval on the '.date('Y-m-d',strtotime($this->leave_approval->leave_request->created_at)).'('.\Carbon\Carbon::parse($this->leave_approval->leave_request->created_at)->diffForHumans().') in this stage of approval.')
        ->line('The document has been moved to the'.$this->nextstage->name.'and is to be appoved by'.$this->nextstage->user->name)
        // ->action('View Document',  route("documents.view",$this->review->document->id))
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
            'subject'=>$this->leave_approval->leave_request->leave->name.' -Document Passed an Approval Stage',
            'message'=>'The document, '.$this->leave_approval->leave_request->leave->name.' which you submitted for approval in the '.$this->stage->workflow->name.'has been approved at the '.$this->stage->name.' by '.$this->stage->user->name,
            // 'action'=>route('documents.showreview', ['id'=>$this->review->document->id]),
            'type'=>'LeaveRequest'
        ]);

    }
}
