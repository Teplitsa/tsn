<?php

namespace App\Http\Controllers\InternalApi;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Notifications extends Controller
{
    public function load(Request $request)
    {
        return fractal()
            ->collection($request->user()->unreadNotifications)
            ->transformWith(new \App\Transformers\Notification\NotificationRecordBell())
            ->toArray();
    }
}
