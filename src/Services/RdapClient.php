<?php

namespace Yu\DnsCheck\Services;

use Illuminate\Support\Facades\Cache;
use Yu\WebToolsCore\Exceptions\ToolException;
use Yu\WebToolsCore\Http\SafeFetcher;

/**
 * Reads the registration of a domain over RDAP, the JSON successor of WHOIS: the server of each zone comes
 * from the IANA bootstrap list. Zones without an RDAP server, and servers that fail, give null.
 */
class RdapClient
{
    private const int MAX_BYTES = 512 * 1024;

    public function __construct(private SafeFetcher $fetcher) {}

    /**
     * @return array{expires: int|null, registrar: string|null}|null
     */
    public function domain(string $domain): ?array
    {
        $server = $this->server($domain);

        if ($server === null) {
            return null;
        }

        try {
            $response = $this->fetcher->fetch(rtrim($server, '/').'/domain/'.$domain, null, self::MAX_BYTES, 'application/rdap+json');
        } catch (ToolException) {
            return null;
        }

        $data = json_decode($response->body, true);

        if ($response->status !== 200 || !is_array($data)) {
            return null;
        }

        $expires = null;
        foreach ($data['events'] ?? [] as $event) {
            if (($event['eventAction'] ?? null) === 'expiration' && is_string($event['eventDate'] ?? null)) {
                $expires = strtotime($event['eventDate']) ?: null;
            }
        }

        return ['expires' => $expires, 'registrar' => $this->registrar($data)];
    }

    /**
     * The RDAP server of the longest zone of the domain the bootstrap list knows, e.g. "ua" for "yu.net.ua".
     */
    private function server(string $domain): ?string
    {
        $servers = $this->servers();

        $labels = explode('.', $domain);
        for ($i = 1; $i < count($labels); $i++) {
            $zone = implode('.', array_slice($labels, $i));

            if (isset($servers[$zone])) {
                return $servers[$zone];
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function registrar(array $data): ?string
    {
        foreach ($data['entities'] ?? [] as $entity) {
            if (in_array('registrar', $entity['roles'] ?? [], true)) {
                foreach ($entity['vcardArray'][1] ?? [] as $field) {
                    if (($field[0] ?? null) === 'fn' && is_string($field[3] ?? null)) {
                        return $field[3];
                    }
                }

                return is_string($entity['handle'] ?? null) ? $entity['handle'] : null;
            }
        }

        return null;
    }

    /**
     * Zone => RDAP server, from the IANA list; kept for a day, but a failed download is not kept.
     *
     * @return array<string, string>
     */
    private function servers(): array
    {
        $cached = Cache::get('dns-check:rdap-bootstrap');

        if (is_array($cached)) {
            return $cached;
        }

        try {
            $list = json_decode($this->fetcher->fetch((string) config('dns-check.rdap_bootstrap'), null, 2 * 1024 * 1024)->body, true);
        } catch (ToolException) {
            return [];
        }

        $servers = [];
        foreach (is_array($list) ? $list['services'] ?? [] : [] as [$zones, $urls]) {
            foreach ($zones as $zone) {
                if (is_string($urls[0] ?? null)) {
                    $servers[strtolower($zone)] = $urls[0];
                }
            }
        }

        if ($servers !== []) {
            Cache::put('dns-check:rdap-bootstrap', $servers, 86400);
        }

        return $servers;
    }
}
