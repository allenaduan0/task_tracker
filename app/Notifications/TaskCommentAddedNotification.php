<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCommentAddedNotification extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment on Task')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('A new comment was added to the task: "' . $this->task->title . '".')
            ->action('View Task', route('tasks.show', $this->task->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assigned_by' => auth()->user()->name ?? 'System',
            'project' => $this->task->project->name,
            'message' => 'A new comment was added to your task: "' . $this->task->title . '"',
            'task_id' => $this->task->id,
        ];
    }
}
