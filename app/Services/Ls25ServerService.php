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
        $serverName = $this->value($xml, [
            'Server/serverName',
            'Server/@name',
            '@serverName',
            'serverName',
            '@name',
            'name',
        ], 'LS25 Server');

        $mapName = $this->value($xml, [
            'Server/mapName',
            'Server/map',
            '@mapName',
            'mapName',
            'map',
        ], '-');

        $players = (int)$this->value($xml, [
            'Slots/@numUsed',
            'Slots/numUsed',
            'slots/@numUsed',
            'slots/numUsed',
            '@numUsed',
            'numUsed',
            'players',
            'numPlayers',
        ], 0);

        $maxPlayers = (int)$this->value($xml, [
            'Slots/@capacity',
            'Slots/capacity',
            'slots/@capacity',
            'slots/capacity',
            '@capacity',
            'capacity',
            'maxPlayers',
            'slots',
        ], 0);

        $hasPasswordRaw = $this->value($xml, [
            'Server/@hasPassword',
            'Server/hasPassword',
            '@hasPassword',
            'hasPassword',
            'password',
        ], 'Ja');

        $modsRaw = $this->value($xml, [
            'Mods/@mods',
            'Mods/mods',
            '@mods',
            'mods',
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

    protected function value(\SimpleXMLElement $xml, array $paths, mixed $default = null): mixed
    {
        foreach ($paths as $path) {
            $value = $this->readPath($xml, $path);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $default;
    }

    protected function readPath(\SimpleXMLElement $xml, string $path): ?string
    {
        $parts = explode('/', $path);
        $node = $xml;

        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            if (str_starts_with($part, '@')) {
                $attrName = substr($part, 1);
                $attrs = $node->attributes();

                if (!isset($attrs[$attrName])) {
                    return null;
                }

                return trim((string)$attrs[$attrName]);
            }

            if (!isset($node->{$part})) {
                return null;
            }

            $node = $node->{$part};
        }

        return trim((string)$node);
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