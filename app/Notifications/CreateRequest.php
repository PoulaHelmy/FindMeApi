<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class CreateRequest extends Notification
{
    use Queueable;
    public $user;
    public $requestt;
    public function __construct($id,$request_id){
      $this->user=$id;
      $this->requestt=$request_id;
    }
    public function via($notifiable){
        return ['database','mail','broadcast'];
    }
    public function toMail($notifiable){
        $user=User::find($this->user);
        $urls = url('http://localhost:4200/dashboard');
        // $url = url('/api/auth/signup/activate/'.$notifiable->activation_token);
        return (new MailMessage)
            ->subject('Hi '.$user->name.' New Request Created')
            ->line('You Have New Request Created For One Of Your Items')
            ->line(' To See Your New Requests')
            ->action('Go To Your DashBoard', url($urls))
            ->line('Thank you for using our application!');

    }
    public function toArray($notifiable){
        return [
            'body'=>'You Have New Request!!',
            'request_id'=>$this->requestt,
        ];
    }
    public function toBroadcast($notifiable){
        return new BroadcastMessage([
            'body'=>'You Have New Request!!',
            'request_id'=>$this->requestt,
        ]);
    }









}//end Of Class




//    public function toPushNotification($notifiable)
//    {
//        return PusherMessage::create()
//            ->title('New Request Created')
//            ->body("Your Have New Request!!");
//    }
