<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * D2: both the domain and www.<domain> point to a server, so the site opens whichever one people type.
 */
class WebRecordsCheck implements DnsRule
{
    public const string SNIPPET = <<<'TEXT'
        example.com.       A      203.0.113.10
        www.example.com.   CNAME  example.com.
        TEXT;

    public function run(Snapshot $site): Check
    {
        $evidence = array_filter([
            $site->ips === [] ? null : $site->domain.' → '.implode(', ', array_slice($site->ips, 0, 4)),
            $site->wwwIps === [] ? null : 'www.'.$site->domain.' → '.implode(', ', array_slice($site->wwwIps, 0, 4)),
        ]);

        return match (true) {
            $site->ips !== [] && $site->wwwIps !== [] => Check::pass('D2', 10, 'ok', evidence: array_values($evidence)),
            $site->ips !== [] => Check::warn('D2', 6, 10, 'no_www', ['domain' => $site->domain], array_values($evidence), self::SNIPPET),
            $site->wwwIps !== [] => Check::warn('D2', 6, 10, 'www_only', ['domain' => $site->domain], array_values($evidence), self::SNIPPET),
            default => Check::fail('D2', 10, 'none', ['domain' => $site->domain], snippet: self::SNIPPET),
        };
    }
}
