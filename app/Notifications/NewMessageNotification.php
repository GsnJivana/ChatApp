<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMessageNotification extends Notification
{
    use Queueable;
    public $message;
    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        //
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_name' => $this->message->user->name,
            'text' => 'Vous a envoyé un message.',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'sender_name' => $this->message->user->name,
            'text' => 'Vous a envoyé un nouveau message !',
        ]);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
