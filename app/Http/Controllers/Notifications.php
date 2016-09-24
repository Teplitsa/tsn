<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Notifications\DatabaseNotification;

class Notifications extends Controller
{
    public function read(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return redirect($notification->data['link']);
    }
}
