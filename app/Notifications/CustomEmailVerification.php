<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomEmailVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
     * Get the verification URL for the given user.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        // Get the user's preferred language, fallback to default locale
        $lang = $notifiable->language ?? config('app.fallback_locale');

        // Custom frontend URL (API URL)
        $frontendUrl = config('app.frontend_url', 'http://localhost:8000');

        // Generate the signed URL (this includes the verification ID and hash)
        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Parse the signed URL to extract its components and add the custom query parameters
        $parsedUrl = parse_url($signedUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        // Add additional query parameters for expires and signature
        $expires = Carbon::now()->addMinutes(config('auth.verification.expire', 60))->timestamp;
        $signature = sha1($signedUrl); // You can modify this depending on how the signature is generated

        // Add expires and signature to query parameters
        $queryParams['expires'] = $expires;
        $queryParams['signature'] = $signature;

        // Rebuild the URL with the new query parameters
        $newQuery = http_build_query($queryParams);
        // $finalUrl = $frontendUrl . '/api/email/verify/' . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()) . '?' . $newQuery;
        $finalUrl = $frontendUrl . '/' . $lang . '/verify-email/' . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()) . '?' . $newQuery;

        return $finalUrl;
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
          $verificationUrl = $this->verificationUrl($notifiable);

        // Set the locale to the user's preferred language
        $lang = $notifiable->language ?? config('app.fallback_locale');
        App::setLocale($lang);
        return (new MailMessage)
            ->subject(__('auth.verify_email_subject'))
            ->line(__('auth.verify_email_intro'))
            ->action(__('auth.verify_email_button'), $verificationUrl)
            ->line(__('auth.verify_email_outro'));
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
