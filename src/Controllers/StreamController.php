<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;


class StreamController extends BaseController
{
    // Each event sets a cache flag when triggered, which this method checks
    // to determine if it should notify the client.
    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $cache = \Config\Services::cache();

        $start = time();
        while (time() - $start < 15) { // Keep connection open for 15 seconds
            $event1Triggered = $cache->get('event1_triggered');
            if ($event1Triggered === '1') {
                //log_message('info', 'SSE: event 1 triggered, notifying client and clearing cache flag.');
                echo "event: event1\n";
                echo 'data: {"last_event_1": "' . date('Y-m-d H:i:s') . '"}' . "\n\n";
                ob_flush(); flush();
                $cache->delete('event1_triggered');
            }
            // Send a comment every 15 seconds to keep the connection alive
            // if ((time() - $start) % 15 === 0) {
            //     echo ": keepalive\n\n";
            //     ob_flush(); flush();
            // }
            // Just to avoid a tight loop, sleep for 2 seconds
            sleep(2);
        }
        exit;
    }
}
