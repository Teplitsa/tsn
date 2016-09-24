<?php
namespace App\Transformers\Notification;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationRecordBell
{

    public function transform(DatabaseNotification $notification)
    {
        return [
            'text' => $notification->data['text'],
            'id'   => $notification->id,
            'link' => '/notification/' . $notification->id,
            'icon' => $notification->data['icon'],
            'date' => $notification->{$notification->getCreatedAtColumn()}->format('c'),
        ];
    }
}
