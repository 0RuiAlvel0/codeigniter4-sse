<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use SseModule\Config\Sse;

class StreamController extends Controller
{
    // Each event sets a cache flag when triggered, which this method checks
    // to determine if it should notify the client.
    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $cache = \Config\Services::cache();

        $config = new Sse();

        $start = time();
        while (time() - $start < $config->streamDuration) {
            
            foreach ($config->events as $eventName => $cacheKey) {
                $flag = $cache->get($cacheKey);
                if ($flag === '1') {
                    echo "event: {$eventName}\n";
                    echo 'data: {"timestamp": "' . date('Y-m-d H:i:s') . '"}' . "\n\n";
                    ob_flush(); flush();
                    $cache->delete($cacheKey);
                }
            }

            // Optional to increase stability (although never really required on my tests):
            // Send a comment every 15 seconds to keep the connection alive
            // if ((time() - $start) % 15 === 0) {
            //     echo ": keepalive\n\n";
            //     ob_flush(); flush();
            // }

            // Just to avoid a tight loop, sleep for X seconds (set on environment variable)
            sleep($config->pollInterval);
        }
        exit;
    }
}