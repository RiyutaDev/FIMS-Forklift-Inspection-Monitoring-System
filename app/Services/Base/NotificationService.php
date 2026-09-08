<?php

namespace App\Services\Base;

class NotificationService extends BaseService
{
    public function send(mixed $recipient, string $message): array
    {
        return [
            'recipient' => $recipient,
            'message' => $message,
            'sent' => true,
        ];
    }
}
