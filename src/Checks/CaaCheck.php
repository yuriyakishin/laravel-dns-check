<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * D4: a CAA record names the authorities allowed to issue SSL certificates for the domain.
 */
class CaaCheck implements DnsRule
{
    public const string SNIPPET = 'example.com.  CAA  0 issue "letsencrypt.org"';

    public function run(Snapshot $site): Check
    {
        return $site->caa === []
            ? Check::warn('D4', 2, 5, 'none', snippet: self::SNIPPET)
            : Check::pass('D4', 5, 'ok', evidence: $site->caa);
    }
}
