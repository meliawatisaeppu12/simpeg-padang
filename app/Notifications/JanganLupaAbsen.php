<?php

namespace App\Notifications;

use App\View\Components\app;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\ApnsConfig;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\ApnsConfig as ResourcesApnsConfig;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class JanganLupaAbsen extends Notification
{
    use Queueable;

    private $title;
    private $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        $apn_config = ResourcesApnsConfig::create()->setPayload([
            'aps' => [
                'sound' => 'default'
            ]
        ]);
        return FcmMessage::create()->setNotification(app(FcmNotification::class)->setTitle($this->title)->setBody($this->body))->setApns($apn_config);
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
