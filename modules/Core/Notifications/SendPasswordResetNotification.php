<?php

namespace Modules\Core\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Core\Emails\PasswordResetMail;
use Modules\Core\Entities\User\PasswordReset;

class SendPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * @var PasswordReset
     */
    public $passwordReset;

    /**
     * Create a new notification instance.
     *
     * @param PasswordReset $passwordReset
     */
    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return PasswordResetMail
     */
    public function toMail($notifiable)
    {
        return (new PasswordResetMail($notifiable, $this->passwordReset->pin,
            PasswordReset::DEFAULT_EXPIRY_IN_MINUTES))
            ->to($notifiable->email)
            ->subject('Reset Password - Kanact Media');
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
