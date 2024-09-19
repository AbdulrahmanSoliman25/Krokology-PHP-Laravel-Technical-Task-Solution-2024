<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TodoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $todo;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct($todo, $action)
    {
        $this->todo = $todo;
        $this->action = $action;
    }
    public function getTodo()
    {
        return $this->todo;
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
        return (new MailMessage)
            ->subject('Todo Notification')
            ->line("Your todo item titled \"{$this->todo->title}\" has been {$this->action}.")
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'todo_id' => $this->todo->id,
            'action' => $this->action,
        ];
    }
}
