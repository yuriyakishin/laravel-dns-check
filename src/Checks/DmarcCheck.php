<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * M3: DMARC tells receivers what to do with mail that fails SPF and DKIM, and where to send reports about it.
 */
class DmarcCheck implements DnsRule
{
    public const string SNIPPET = '_dmarc.example.com.  TXT  "v=DMARC1; p=quarantine; rua=mailto:dmarc@example.com"';

    public function run(Snapshot $site): Check
    {
        $dmarc = $site->dmarc;

        if ($dmarc === []) {
            return Check::fail('M3', 15, 'none', snippet: self::SNIPPET);
        }

        if (count($dmarc) > 1) {
            return Check::fail('M3', 15, 'multiple', ['count' => count($dmarc)], $dmarc, self::SNIPPET);
        }

        $tags = [];
        foreach (explode(';', $dmarc[0]) as $pair) {
            [$name, $value] = array_pad(array_map('trim', explode('=', $pair, 2)), 2, '');
            $tags[strtolower($name)] = strtolower($value);
        }

        $policy = $tags['p'] ?? '';

        return match (true) {
            !in_array($policy, ['none', 'quarantine', 'reject'], true) => Check::fail('M3', 15, 'invalid', evidence: $dmarc, snippet: self::SNIPPET),
            $policy === 'none' => Check::warn('M3', 7, 15, 'monitor', evidence: $dmarc, snippet: self::SNIPPET),
            ($tags['rua'] ?? '') === '' => Check::warn('M3', 12, 15, 'no_reports', ['policy' => $policy], $dmarc, self::SNIPPET),
            default => Check::pass('M3', 15, 'ok', ['policy' => $policy], $dmarc),
        };
    }
}
