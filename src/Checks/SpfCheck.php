<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * M2: one SPF record lists the servers allowed to send mail for the domain, ends with ~all or -all
 * and needs at most 10 DNS lookups, the limit after which receivers treat it as broken.
 */
class SpfCheck implements DnsRule
{
    public const string SNIPPET = <<<'TEXT'
        example.com.  TXT  "v=spf1 include:_spf.google.com ~all"

        ; a domain that sends no mail at all:
        example.com.  TXT  "v=spf1 -all"
        TEXT;

    public function run(Snapshot $site): Check
    {
        $spf = $site->spf;

        if ($spf === []) {
            return Check::fail('M2', 15, 'none', snippet: self::SNIPPET);
        }

        if (count($spf) > 1) {
            return Check::fail('M2', 15, 'multiple', ['count' => count($spf)], $spf, self::SNIPPET);
        }

        $record = strtolower(trim($spf[0]));
        $lookups = (int) $site->spfLookups;

        return match (true) {
            preg_match('/(?:^|\s)\+?all\b/', $record) === 1 => Check::fail('M2', 15, 'pass_all', evidence: $spf, snippet: self::SNIPPET),
            $lookups > 10 => Check::fail('M2', 15, 'too_many_lookups', ['count' => $lookups], $spf, self::SNIPPET),
            preg_match('/(?:^|\s)\?all\b/', $record) === 1 => Check::warn('M2', 8, 15, 'neutral', evidence: $spf, snippet: self::SNIPPET),
            preg_match('/(?:^|\s)[~-]all\b|redirect=/', $record) !== 1 => Check::warn('M2', 8, 15, 'no_all', evidence: $spf, snippet: self::SNIPPET),
            default => Check::pass('M2', 15, 'ok', ['count' => $lookups], $spf),
        };
    }
}
