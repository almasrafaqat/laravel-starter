<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetSuccessNotification extends Notification
{
    use Queueable;
    protected $user;
    public $resetUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject(__('auth.password_reset_success_subject'))
            ->greeting(__('messages.hello_user', ['name' => $this->user->name]))
            ->line(__('auth.password_reset_success_message'))
            ->action(__('auth.password_reset_success_button'), $this->resetUrl)
            ->line(__('auth.password_reset_success_outro'));
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
