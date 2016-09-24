<?php

namespace App\Notifications\Users;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewColleague extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    public $colleague;

    /**
     * Create a new notification instance.
     * @param User $colleague
     */
    public function __construct(User $colleague)
    {
        $this->colleague = $colleague;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'broadcast',
            'database',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' => 'В нашей компании появился новый сотрудник! Поприветствуем: ' . $this->colleague->full_name,
            'icon' => 'fa fa-user-plus',
            'link' => route('employees.show', [$this->colleague]),
        ];
    }
}
