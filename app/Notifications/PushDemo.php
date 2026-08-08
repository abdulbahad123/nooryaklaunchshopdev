<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PushDemo extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $buttonText;
    public $buttonURL;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $buttonText, $buttonURL)
    {
        $this->title = $title;
        $this->message = $message;
        $this->buttonText = $buttonText;
        $this->buttonURL = $buttonURL;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        $icon = 'assets/front/img/pushnotification_icon.png';
        if (isset($notifiable->user_id)) {
            $userIcon = 'assets/front/img/user/push_icon_' . $notifiable->user_id . '.png';
            if (file_exists(public_path($userIcon))) {
                $icon = $userIcon;
            } else {
                $userBs = \DB::table('user_basic_settings')->where('user_id', $notifiable->user_id)->first();
                if ($userBs && !empty($userBs->web_app_image)) {
                    $icon = 'assets/front/img/user/' . $userBs->web_app_image;
                }
            }
        }

        $push = (new WebPushMessage)
                ->title($this->title)
                ->icon(url($icon))
                ->action($this->buttonText, $this->buttonURL);

        if (!empty($this->message)) {
            $push = $push->body($this->message);
        }

        return $push;
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
}
