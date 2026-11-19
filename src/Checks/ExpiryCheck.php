<?php

declare(strict_types=1);

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * D1: the domain registration does not run out soon. A lapsed domain takes the site and the mail down at once.
 */
class ExpiryCheck implements DnsRule
{
    public function run(Snapshot $site): Check
    {
        $expires = $site->registration['expires'] ?? null;

        if ($expires === null) {
            return Check::skip('D1', 10, $site->registration === null ? 'no_rdap' : 'unknown');
        }

        $days = (int) floor(($expires - now()->getTimestamp()) / 86400);
        $replace = ['days' => $days, 'date' => date('Y-m-d', $expires)];
        $evidence = array_values(array_filter([$site->registration['registrar'] ?? null]));

        return match (true) {
            $days < 0 => Check::fail('D1', 10, 'expired', $replace, $evidence),
            $days < 14 => Check::fail('D1', 10, 'urgent', $replace, $evidence),
            $days < 45 => Check::warn('D1', 5, 10, 'soon', $replace, $evidence),
            default => Check::pass('D1', 10, 'ok', $replace, $evidence),
        };
    }
}
