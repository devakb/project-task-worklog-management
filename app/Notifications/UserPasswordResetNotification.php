<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class UserPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $key = str()->random(20);
        $timestamp = now()->addMinutes(10);

        Cache::set("password-reset-{$notifiable->id}", $key, $timestamp);

        return (new MailMessage)
                    ->greeting("Hello! " . $notifiable->name)
                    ->line('Someone is attempting to reset the password of your account.')
                    ->line('If this was you, please click the "Reset Password" button to reset your password.')
                    ->action('Reset Password', route('password.approve', ['key' => $key, "timestamp" => $timestamp->format("Ymdhis"), "email" => $notifiable->email]))
                    ->line("This link will expire in 10 minutes, so be sure to use it right away.")
                    ->line("If you didn't request it, please ignore this email. Your account is safe.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
