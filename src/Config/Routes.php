<?php
// SSE route
$routes->get('sse/stream', '\SseModule\Controllers\StreamController::stream');