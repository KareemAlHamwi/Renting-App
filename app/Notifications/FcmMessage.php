<?php

namespace App\Notifications;

class FcmMessage {
    public function __construct(
        public string $title,
        public string $body,
        public array $data = [],
    ) {
    }
}
