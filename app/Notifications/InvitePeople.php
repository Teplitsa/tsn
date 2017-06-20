<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitePeople extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$house)
    {
        $this->user=$user;
        $this->house=$house;
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
                     ->subject('Вас пригласили в ' . config('app.name'))
                    ->line('Вы приглашены в Ананас.ТСН')
                    ->line($this->user->full_name.' пригласил(a) вас стать участником ТСН по адресу: '.$this->house->address.'. Зарегистрируйтесь, чтобы принять участие в голосованиях вашего дома')
                    ->action('Зарегистрироваться', 'https://tsn.ananas-web.ru/register?house='.$this->house->id);
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
