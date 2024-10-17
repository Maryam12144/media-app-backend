<?php

namespace Modules\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Core\Emails\PhoneResetMail;
use Modules\Core\Entities\User\PhoneReset;
use Modules\Core\Libraries\Queue;

class SendPhoneResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var PhoneReset
     */
    public $phoneReset;

    /**
     * Create a new notification instance.
     *
     * @param PhoneReset $phoneReset
     */
    public function __construct(PhoneReset $phoneReset)
    {
        $this->phoneReset = $phoneReset;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => Queue::QUEUE_NAME_GENERAL_IMPORTANT,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return PhoneResetMail
     */
    public function toMail($notifiable)
    {
        return (new PhoneResetMail($notifiable, $this->phoneReset,
            PhoneReset::DEFAULT_EXPIRY_IN_MINUTES))
            ->to($notifiable->email)
            ->subject('Reset Phone Number - Kanact Media');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
