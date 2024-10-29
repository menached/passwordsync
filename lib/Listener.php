<?php

namespace OCA\PasswordSync;

use OCP\User\Events\BeforeUserUpdatedEvent;
use OCP\ILogger;

class Listener {
    private $logger;

    public function __construct(ILogger $logger) {
        $this->logger = $logger;
    }

    public function handle(BeforeUserUpdatedEvent $event) {
        if ($event->getDisplayName() !== $event->getOldDisplayName()) {
            // Only sync password changes, not other profile updates
            return;
        }

        $username = $event->getUser()->getUID();
        $newPassword = $event->getUser()->getPassword();

        $this->syncPasswordWithLinuxServer($username, $newPassword);
    }

    private function syncPasswordWithLinuxServer($username, $password) {
        $url = 'https://your-linux-server.com/sync_password';

        $data = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        // Log the response for debugging
        $this->logger->info("Password sync response for user $username: $result");
    }
}

