<?php

namespace SseModule\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Install extends BaseCommand
{
	protected $group       = 'SSE';
	protected $name        = 'sse:install';
	protected $description = 'Installs the SSE module configuration and assets';

	public function run(array $params)
	{
		CLI::write('Installing SSE module...', 'yellow');

		$source = __DIR__ . '/../JS/sse-client.js';
		$target = ROOTPATH . 'public/js/sse-client.js';

		if (! is_dir(dirname($target))) {
			mkdir(dirname($target), 0755, true);
		}

		if (copy($source, $target)) {
			CLI::write('JS client copied to public/js/', 'green');
		} else {
			CLI::error('Failed to copy JS client.');
		}

		$envPath = ROOTPATH . '.env';
		if (file_exists($envPath)) {
			$envContent = file_get_contents($envPath);
			if (! str_contains($envContent, 'SSE_STREAM_DURATION')) {
				// TODO: #1 To be improved -> ask the user during installation for event names and keys.
				// TODO: #2 To be improved -> prompt the user for event names and perform template replacement in sse-client.js to inject those event names.
				file_put_contents($envPath, "\n# codeigniter-sse related settings (this will auto-remove when you uninstall the add-on with php spark sse:uninstall)\n\nSSE_STREAM_DURATION=15\nSSE_POLL_INTERVAL=2\nSSE_EVENTS=event_name1:event_cache_key1,event_name2:event_cache_key2\n", FILE_APPEND);
				CLI::write('Default SSE config added to .env', 'green');
			} else {
				CLI::write('SSE config already exists in .env', 'blue');
			}
		}

		CLI::write('SSE module installed successfully!', 'green');
	}
}