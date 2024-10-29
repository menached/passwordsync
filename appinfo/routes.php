<?php

use OCP\AppFramework\Router;
use OCA\PasswordSync\AppInfo\Application;

$app = new Application();
$app->registerRoutes($this, [
    'routes' => [
        ['name' => 'sync#doSync', 'url' => '/sync', 'verb' => 'POST'],
    ]
]);

