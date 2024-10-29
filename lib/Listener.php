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
        try {
            if ($event->getDisplayName() !== $event->getOldDisplayName()) {
                $this->logger->debug("Display name updated for user {$event->getUser()->getUID()}, no password sync required.");
                return;
            }

            $username = $event->getUser()->getUID();
            $newPassword = $event->getUser()->getPassword();

            $this->logger->info("Password sync initiated for user $username.");
            $this->syncPasswordWithLinuxServer($username, $newPassword);
            $this->logger->info("Password sync completed for user $username.");
        } catch (\Exception $e) {
            $this->logger->error("Password sync failed for user {$event->getUser()->getUID()}: " . $e->getMessage());
        }
    }

    private function syncPasswordWithLinuxServer($username, $password) {
        $url = 'https://your-linux-server.com/sync_password';
        $data = json_encode(['username' => $username, 'password' => $password]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($result === false) {
            $error = curl_error($ch);
            $this->logger->error("Curl error during password sync for $username: $error");
            curl_close($ch);
            throw new \Exception("Password sync failed for $username: $error");
        }

        if ($httpCode !== 200) {
            $this->logger->warning("Password sync request for $username returned HTTP code $httpCode with response: $result");
            curl_close($ch);
            throw new \Exception("Password sync failed for $username, received HTTP code $httpCode");
        }

        curl_close($ch);
        $this->logger->debug("Password sync HTTP response for $username: $result");
    }
}

