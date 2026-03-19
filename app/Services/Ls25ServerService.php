<?php
namespace App\Services;

class Ls25ServerService
{
    protected array $config;

    public function __construct()
    {
        $this->config = require BASE_PATH . '/app/Config/ls25.php';
    }

    public function getStatus(): array
    {
        $url = $this->config['endpoint'] ?? '';
        $timeout = (int)($this->config['timeout'] ?? 3);

        if ($url === '') {
            return $this->offline();
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => $timeout,
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response === false || $response === '') {
                return $this->offline();
            }

            $data = json_decode($response, true);

            if (!is_array($data)) {
                return $this->offline();
            }

            return [
                'online' => true,
                'name' => $data['serverName'] ?? ($data['name'] ?? 'LS25 Server'),
                'map' => $data['mapName'] ?? ($data['map'] ?? '-'),
                'players' => (int)($data['players'] ?? 0),
                'maxPlayers' => (int)($data['maxPlayers'] ?? 0),
                'hasPassword' => !empty($data['hasPassword']),
                'mods' => !empty($data['mods']),
            ];
        } catch (\Throwable $e) {
            return $this->offline();
        }
    }

    protected function offline(): array
    {
        return [
            'online' => false,
            'name' => 'LS25 Server',
            'map' => '-',
            'players' => 0,
            'maxPlayers' => 0,
            'hasPassword' => false,
            'mods' => false,
        ];
    }
}
