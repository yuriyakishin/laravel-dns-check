<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * M1: MX records say which servers receive the domain's mail, and every one of them exists.
 */
class MxCheck implements DnsRule
{
    public const string SNIPPET = <<<'TEXT'
        example.com.  MX  10 mx1.your-mail-provider.com.

        ; or, when the domain never receives mail (a "null MX"):
        example.com.  MX  0 .
        TEXT;

    public function run(Snapshot $site): Check
    {
        if ($site->hasNullMx()) {
            return Check::pass('M1', 10, 'null');
        }

        if ($site->mx === []) {
            return Check::warn('M1', 5, 10, 'none', snippet: self::SNIPPET);
        }

        $evidence = array_map(fn (array $mx): string => $mx['priority'].' '.$mx['host'], $site->mx);
        $broken = array_values(array_filter($site->mx, fn (array $mx): bool => !$mx['resolves']));

        return $broken === []
            ? Check::pass('M1', 10, 'ok', ['count' => count($site->mx)], $evidence)
            : Check::fail('M1', 10, 'broken', ['hosts' => implode(', ', array_column($broken, 'host'))], $evidence, self::SNIPPET);
    }
}
