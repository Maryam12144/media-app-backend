<?php

namespace Modules\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Core\Emails\EmailVerificationMail;
use Modules\Core\Entities\User\EmailVerification;

class SendEmailVerificationNotification extends Notification
{
    use Queueable;

    /**
     * @var EmailVerification
     */
    public $emailVerification;

    /**
     * Create a new notification instance.
     *
     * @param EmailVerification $emailVerification
     */
    public function __construct(EmailVerification $emailVerification)
    {
        $this->emailVerification = $emailVerification;
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
     * @return EmailVerificationMail
     */
    public function toMail($notifiable)
    {
        /** 
         * Email verification via url.
    `   */

        // return (new EmailVerificationMail($notifiable, $this->emailVerification->url))
        //     ->to($notifiable->email)
        //     ->subject('Verify Your Account - Kanact Media');

        /** 
         * Email verification via pin.
    `   */

        return (new EmailVerificationMail($notifiable, $this->emailVerification->pin))
            ->to($notifiable->email)
            ->subject('Verify Your Account - Kanact Media');
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
