<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * D3: at least two name servers in different networks, so the domain still answers when one of them is down.
 */
class NameserversCheck implements DnsRule
{
    public function run(Snapshot $site): Check
    {
        $evidence = array_map(
            fn (array $server): string => $server['name'].($server['ips'] === [] ? '' : ' ('.implode(', ', array_slice($server['ips'], 0, 2)).')'),
            $site->nameservers,
        );

        if (count($site->nameservers) < 2) {
            return Check::fail('D3', 5, 'single', ['count' => count($site->nameservers)], $evidence);
        }

        $networks = [];
        foreach ($site->nameservers as $server) {
            foreach ($server['ips'] as $ip) {
                $networks[$this->network($ip)] = true;
            }
        }

        return count($networks) < 2
            ? Check::warn('D3', 2, 5, 'same_network', ['count' => count($site->nameservers)], $evidence)
            : Check::pass('D3', 5, 'ok', ['count' => count($site->nameservers)], $evidence);
    }

    /**
     * The /24 of an IPv4 address or the /48 of an IPv6 one.
     */
    private function network(string $ip): string
    {
        if (str_contains($ip, ':')) {
            return implode(':', array_slice(explode(':', inet_ntop(inet_pton($ip)) ?: $ip), 0, 3));
        }

        return implode('.', array_slice(explode('.', $ip), 0, 3));
    }
}
