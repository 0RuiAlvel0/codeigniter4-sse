<?php

namespace SseModule\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Uninstall extends BaseCommand
{
    protected $group       = 'SSE';
    protected $name        = 'sse:uninstall';
    protected $description = 'Removes SSE module assets and configuration';

    public function run(array $params)
    {
        CLI::write('Uninstalling SSE module...', 'yellow');

        // Remove JS client
        $target = ROOTPATH . 'public/js/sse-client.js';
        if (file_exists($target)) {
            unlink($target);
            CLI::write('Removed sse-client.js from public/js/', 'green');
        } else {
            CLI::write('sse-client.js not found in public/js/.', 'blue');
        }

        // Remove .env entries
        $envPath = ROOTPATH . '.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $envContent = preg_replace('/^SSE_.*$/m', '', $envContent);
            file_put_contents($envPath, $envContent);
            CLI::write('Removed SSE config from .env', 'green');
        }

        CLI::write('SSE module uninstalled successfully!', 'green');
    }
}