<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatchingItems extends Notification
{
    use Queueable;

    private $user_id;
    private $user_name;

    public function __construct($user_id, $user_name)
    {
        $this->user_id = $user_id;
        $this->user_name = $user_name;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $urls = url('https://findme-ui.netlify.app/dashboard/matching');
        return (new MailMessage)
            ->subject('Your Matching List Have New Items')
            ->line('Hi ' . $this->user_name . ' There Are Many Items Much Similar To Your Items')
            ->line('Please check If One Of It Will May Be Your Item')
            ->line('You Can Check Them Now')
            ->action('Go To Your DashBoard', url($urls))
            ->line('Thank you for using our application!');

    }

    public function toArray($notifiable)
    {
        return [
            'body' => 'There are Many Items That May Be Your Items'
        ];
    }
}
