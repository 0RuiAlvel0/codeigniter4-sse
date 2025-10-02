<?php
namespace SseModule\Config;

use CodeIgniter\Config\BaseConfig;

class Sse extends BaseConfig
{
    public int $streamDuration;
    public int $pollInterval;
    public array $events;

    public function __construct()
    {
        $this->streamDuration = (int) env('SSE_STREAM_DURATION', 15);
        $this->pollInterval = (int) env('SSE_POLL_INTERVAL', 2);

        $raw = env('SSE_EVENTS', '');
        $this->events = [];

        foreach (explode(',', $raw) as $pair) {
            [$event, $key] = explode(':', $pair);
            $this->events[trim($event)] = trim($key);
        }
    }
}
