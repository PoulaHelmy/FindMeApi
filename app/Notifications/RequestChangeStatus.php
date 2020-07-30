<?php

namespace App\Notifications;

use App\Models\RequestItems;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestChangeStatus extends Notification
{
    use Queueable;
    public $userName;
    public $status;
    public $requestName;
    public $requestID;
    public function __construct($user_name,$status,$req_name,$requestID)
    {
       $this->userName=$user_name;
       $this->status=$status===1?'Approved':'Rejected';;
       $this->requestName=$req_name;
       $this->requestID=$requestID;
    }
    public function via($notifiable)
    {
        return ['mail','database'];
    }
    public function toMail($notifiable)
    {
        $urls = url('http://localhost:4200/dashboard');

        return (new MailMessage)
            ->subject('Hi '.$this->userName.' Your Request Status Has Changed')
            ->line('You Can Check The new Status from Your DashBoard')
            ->line('Your Request '.$this->requestName)
            ->line('The New Status is '.$this->status)
            ->action('Go To Your DashBoard', url($urls))
            ->line('Thank you for using our application!');

    }
    public function toArray($notifiable)
    {
        return [
            'body'=>'Your Request '.$this->requestName.' Status Has Changed',
            'new_status'=>$this->status,
            'request_id'=>$this->requestID,
        ];
    }
}
