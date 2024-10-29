<?php

namespace OCA\PasswordSync\Controller;

use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;

class SyncController extends Controller {
    public function __construct(IRequest $request) {
        parent::__construct('passwordsync', $request);
    }

    public function doSync() {
        // Replace with your sync logic
        return new JSONResponse(['status' => 'sync started']);
    }
}

