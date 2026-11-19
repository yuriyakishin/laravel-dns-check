<?php

namespace Yu\DnsCheck\Services;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Exceptions\ToolException;

/**
 * Finds the registered domain of an address and reads its DNS records and its registration.
 */
class SnapshotCollector
{
    /** SPF stops being valid after 10 lookups; following includes further only wastes queries. */
    private const int SPF_LIMIT = 10;

    private const array BLOCKED_SUFFIXES = ['.local', '.internal', '.localhost', '.lan', '.home.arpa'];

    public function __construct(private DnsLookup $dns, private RdapClient $rdap) {}

    /**
     * @throws ToolException invalid_url when the address has no proper domain, no_domain when DNS does not know it
     */
    public function collect(string $url): Snapshot
    {
        $domain = $this->zone($this->host($url));

        $spf = array_values(array_filter($this->dns->txt($domain), fn (string $txt): bool => stripos(trim($txt), 'v=spf1') === 0));
        $ips = $this->dns->ips($domain);
        $nameservers = array_map(
            fn (array $record): array => ['name' => strtolower($record['target']), 'ips' => $this->dns->ips($record['target'])],
            $this->ownRecords($domain, DNS_NS),
        );

        return new Snapshot(
            domain: $domain,
            ips: $ips,
            wwwIps: $this->dns->ips('www.'.$domain),
            nameservers: $nameservers,
            caa: array_map(fn (array $record): string => ($record['flags'] ?? 0).' '.($record['tag'] ?? '').' "'.($record['value'] ?? '').'"', $this->dns->records($domain, DNS_CAA)),
            mx: $this->mx($domain),
            spf: $spf,
            spfLookups: count($spf) === 1 ? $this->spfLookups($spf[0]) : null,
            dmarc: array_values(array_filter($this->dns->txt('_dmarc.'.$domain), fn (string $txt): bool => stripos(trim($txt), 'v=DMARC1') === 0)),
            dkimSelectors: $this->dkim($domain),
            registration: $this->rdap->domain($domain),
        );
    }

    private function host(string $url): string
    {
        $host = strtolower(rtrim((string) parse_url($url, PHP_URL_HOST), '.'));

        if ($host === ''
            || filter_var(trim($host, '[]'), FILTER_VALIDATE_IP) !== false
            || !preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z][a-z0-9-]{0,61}[a-z0-9]$/', $host)
            || array_any(self::BLOCKED_SUFFIXES, fn (string $suffix): bool => str_ends_with($host, $suffix))) {
            throw new ToolException('invalid_url');
        }

        return $host;
    }

    /**
     * The registered domain: the closest name upwards that has its own name servers,
     * e.g. "yu.net.ua" for "www.yu.net.ua" (while "net.ua" is the registry's zone above it).
     */
    private function zone(string $host): string
    {
        $labels = explode('.', $host);

        for ($i = 0; $i < count($labels) - 1; $i++) {
            $name = implode('.', array_slice($labels, $i));

            if ($this->ownRecords($name, DNS_NS) !== []) {
                return $name;
            }
        }

        throw new ToolException('no_domain');
    }

    /**
     * Records that belong to the name itself: for a CNAME (www.github.com) the resolver answers with the
     * records of the target (github.com), which would make www look like a zone of its own.
     *
     * @return list<array<string, mixed>>
     */
    private function ownRecords(string $name, int $type): array
    {
        return array_values(array_filter(
            $this->dns->records($name, $type),
            fn (array $record): bool => strtolower(rtrim($record['host'] ?? $name, '.')) === $name,
        ));
    }

    /**
     * @return list<array{host: string, priority: int, resolves: bool}>
     */
    private function mx(string $domain): array
    {
        $mx = array_map(fn (array $record): array => [
            'host' => strtolower(rtrim($record['target'] ?? '', '.')),
            'priority' => (int) ($record['pri'] ?? 0),
        ], $this->dns->records($domain, DNS_MX));

        usort($mx, fn (array $a, array $b): int => $a['priority'] <=> $b['priority']);

        return array_map(fn (array $record): array => [
            ...$record,
            'resolves' => $record['host'] !== '' && $this->dns->ips($record['host']) !== [],
        ], $mx);
    }

    /**
     * DNS lookups an SPF record costs: include, a, mx, ptr, exists and redirect, includes and redirects followed.
     *
     * @param  array<string, true>  $seen  domains already followed, against loops
     */
    private function spfLookups(string $record, array &$seen = [], int $count = 0): int
    {
        foreach (preg_split('/\s+/', strtolower(trim($record))) as $term) {
            $term = ltrim($term, '+-~?');

            if (!preg_match('/^(include:|a\b|mx\b|ptr\b|exists:|redirect=)(.*)$/', $term, $match)) {
                continue;
            }

            $count++;

            if ($count > self::SPF_LIMIT) {
                return $count;
            }

            $target = ltrim($match[2], ':=');

            if (in_array($match[1], ['include:', 'redirect='], true) && $target !== '' && !isset($seen[$target])) {
                $seen[$target] = true;
                $included = array_values(array_filter($this->dns->txt($target), fn (string $txt): bool => stripos(trim($txt), 'v=spf1') === 0));

                if ($included !== []) {
                    $count = $this->spfLookups($included[0], $seen, $count);
                }
            }
        }

        return $count;
    }

    /**
     * @return list<string>
     */
    private function dkim(string $domain): array
    {
        $found = [];

        foreach ((array) config('dns-check.dkim_selectors') as $selector) {
            foreach ($this->dns->txt("{$selector}._domainkey.{$domain}") as $txt) {
                // An empty p= is a revoked key
                if (preg_match('/(?:^|;)\s*p=([^;\s]+)/i', $txt)) {
                    $found[] = $selector;
                    break;
                }
            }
        }

        return $found;
    }
}
