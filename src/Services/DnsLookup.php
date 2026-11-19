<?php

declare(strict_types=1);

namespace Yu\DnsCheck\Services;

/**
 * DNS queries through the server's resolver. A name that does not exist, or a failed query, gives no records.
 * Only DNS packets leave the server: no connection is made to the checked hosts.
 */
class DnsLookup
{
    /**
     * @param  int  $type  a DNS_* constant, e.g. DNS_MX
     * @return list<array<string, mixed>>
     */
    public function records(string $name, int $type): array
    {
        return array_values(@dns_get_record($name, $type) ?: []);
    }

    /**
     * @return list<string>
     */
    public function txt(string $name): array
    {
        return array_map(
            fn (array $record): string => implode('', $record['entries'] ?? [$record['txt'] ?? '']),
            $this->records($name, DNS_TXT),
        );
    }

    /**
     * IPv4 and IPv6 addresses of a name.
     *
     * @return list<string>
     */
    public function ips(string $name): array
    {
        return array_map(
            fn (array $record): string => $record['ip'] ?? $record['ipv6'],
            $this->records($name, DNS_A | DNS_AAAA),
        );
    }
}
