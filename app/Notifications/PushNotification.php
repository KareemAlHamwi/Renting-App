<?php

namespace App\Notifications;

use App\Models\User\User;
use App\Notifications\Channels\FcmChannel;
use App\Notifications\Contracts\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification implements ShouldQueue, FcmNotification {
    use Queueable;

    public function __construct(
        private string $title,
        private string $message,
        private array $data = []
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array {
        return [FcmChannel::class];
    }

    public function toFcm(User $notifiable): FcmMessage {
        return new FcmMessage($this->title, $this->message, $this->data);
    }
}
