<?php

namespace App\Notifications\Contracts;

use App\Models\User\User;
use App\Notifications\FcmMessage;

interface FcmNotification {
    public function toFcm(User $notifiable): FcmMessage;
}
