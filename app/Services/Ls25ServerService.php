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
        $timeout = (int)($this->config['timeout'] ?? 5);

        if ($url === '') {
            return $this->offline('Kein Endpoint konfiguriert');
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => $timeout,
                ]
            ]);

            $xmlString = @file_get_contents($url, false, $context);

            if ($xmlString === false || trim($xmlString) === '') {
                return $this->offline('Feed nicht erreichbar');
            }

            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlString);

            if ($xml === false) {
                return $this->offline('Ungültiges XML');
            }

            return $this->mapXmlToStatus($xml);
        } catch (\Throwable $e) {
            return $this->offline($e->getMessage());
        }
    }

    protected function mapXmlToStatus(\SimpleXMLElement $xml): array
    {
        $serverName = $this->firstValue($xml, [
            'serverName',
            'gameserver/name',
            'game/name',
            'name',
        ], '[#HC] FS 25 Mod Server');

        $mapName = $this->firstValue($xml, [
            'mapName',
            'gameserver/mapName',
            'game/mapName',
            'map',
        ], 'Deutschhes-Eck 4Fach Multi');

        $players = (int)$this->firstValue($xml, [
            'players',
            'gameserver/players',
            'game/players',
            'numPlayers',
        ], 0);

        $maxPlayers = (int)$this->firstValue($xml, [
            'maxPlayers',
            'gameserver/maxPlayers',
            'game/maxPlayers',
            'slots',
        ], '16');

        $hasPasswordRaw = $this->firstValue($xml, [
            'hasPassword',
            'gameserver/hasPassword',
            'game/hasPassword',
            'password',
        ], 'Ja');

        $modsRaw = $this->firstValue($xml, [
            'mods',
            'gameserver/mods',
            'game/mods',
            'modCount',
        ], '218');

        return [
            'online' => true,
            'name' => (string)$serverName,
            'map' => (string)$mapName,
            'players' => $players,
            'maxPlayers' => $maxPlayers,
            'hasPassword' => $this->toBool($hasPasswordRaw),
            'mods' => $this->toBool($modsRaw),
            'raw' => $xml->asXML(),
            'error' => null,
        ];
    }

    protected function firstValue(\SimpleXMLElement $xml, array $paths, mixed $default = null): mixed
    {
        foreach ($paths as $path) {
            $parts = explode('/', $path);
            $node = $xml;

            $found = true;
            foreach ($parts as $part) {
                if (!isset($node->{$part})) {
                    $found = false;
                    break;
                }
                $node = $node->{$part};
            }

            if ($found) {
                $value = trim((string)$node);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return $default;
    }

    protected function toBool(mixed $value): bool
    {
        $value = strtolower(trim((string)$value));

        return in_array($value, ['1', 'true', 'yes', 'ja', 'on'], true)
            || (is_numeric($value) && (int)$value > 0);
    }

    protected function offline(?string $reason = null): array
    {
        return [
            'online' => false,
            'name' => 'LS25 Server',
            'map' => '-',
            'players' => 0,
            'maxPlayers' => 0,
            'hasPassword' => false,
            'mods' => false,
            'raw' => null,
            'error' => $reason,
        ];
    }
}