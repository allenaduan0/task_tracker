<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('You have been assigned to a task: "' . $this->task->title . '".')
            ->action('View Task', route('tasks.show', $this->task->id))
            ->line('Thank you for being part of the team!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $user = auth()->user()->name ?? 'System';
        return [
            'assigned_by' => auth()->user()->name ?? 'System',
            'project' => $this->task->project->name,
            'message' => $user . ' assigned you a task: "' . $this->task->title . '"' . '. please check your task tab.',
            'task_id' => $this->task->id,
        ];
    }
}
