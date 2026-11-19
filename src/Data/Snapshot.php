<?php

declare(strict_types=1);

namespace Yu\DnsCheck\Data;

/**
 * The DNS records and the registration of a domain, as the checks need them.
 */
final readonly class Snapshot
{
    /**
     * @param  string  $domain  the registered domain (the zone with its own name servers), e.g. "example.com.ua"
     * @param  list<string>  $ips  A and AAAA addresses of the domain
     * @param  list<string>  $wwwIps  A and AAAA addresses of www.<domain>
     * @param  list<array{name: string, ips: list<string>}>  $nameservers
     * @param  list<string>  $caa  e.g. ['0 issue "letsencrypt.org"']
     * @param  list<array{host: string, priority: int, resolves: bool}>  $mx  sorted by priority
     * @param  list<string>  $spf  TXT records starting with v=spf1
     * @param  int|null  $spfLookups  DNS lookups the SPF record needs, includes followed; null without one SPF record
     * @param  list<string>  $dmarc  TXT records of _dmarc.<domain> starting with v=DMARC1
     * @param  list<string>  $dkimSelectors  selectors whose _domainkey record holds a key
     * @param  array{expires: int|null, registrar: string|null}|null  $registration  null when the zone has no RDAP server
     */
    public function __construct(
        public string $domain,
        public array $ips = [],
        public array $wwwIps = [],
        public array $nameservers = [],
        public array $caa = [],
        public array $mx = [],
        public array $spf = [],
        public ?int $spfLookups = null,
        public array $dmarc = [],
        public array $dkimSelectors = [],
        public ?array $registration = null,
    ) {}

    /**
     * A null MX ("0 ."): the domain says it never receives mail.
     */
    public function hasNullMx(): bool
    {
        return count($this->mx) === 1 && in_array($this->mx[0]['host'], ['', '.'], true);
    }

    /**
     * The facts shown above the report and returned to WebMCP.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'expires' => ($this->registration['expires'] ?? null) !== null ? date(DATE_ATOM, $this->registration['expires']) : null,
            'registrar' => $this->registration['registrar'] ?? null,
            'nameservers' => array_column($this->nameservers, 'name'),
            'mx' => array_column($this->mx, 'host'),
        ];
    }
}
