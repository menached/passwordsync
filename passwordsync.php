<?php

namespace OCA\PasswordSync;

use OCP\AppFramework\App;
use OCP\Util;
use OCP\User\Events\BeforeUserUpdatedEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCA\PasswordSync\Listener;

class Application extends App {
    public function __construct(array $urlParams = []) {
        parent::__construct('passwordsync', $urlParams);
        $container = $this->getContainer();

        // Register the listener
        $container->query(IEventDispatcher::class)
            ->addListener(BeforeUserUpdatedEvent::class, function (BeforeUserUpdatedEvent $event) {
                $listener = $container->query(Listener::class);
                $listener->handle($event);
            });
    }
}

