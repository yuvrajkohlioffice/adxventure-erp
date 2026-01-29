<?php

namespace App\Helpers;

use App\Models\Logs;
use Carbon\Carbon;

class LogHelper
{
    public static function getLoginLogoutTimes($userId)
    {
        $login_time = Logs::where('user_id', $userId)
                          ->where('type', 1)
                          ->whereDate('time', Carbon::today())
                          ->first();

        $logout_time = Logs::where('user_id', $userId)
                           ->where('type', 2)
                           ->whereDate('time', Carbon::today())
                           ->orderBy('id', 'desc')
                           ->first();

        return [
            'login_time' => $login_time ? Carbon::parse($login_time->time)->format('h:i:s A') : null,
            'logout_time' => $logout_time ? Carbon::parse($logout_time->time)->format('h:i:s A') : null,
        ];
      
    }

    

}



