<?php

declare(strict_types=1);

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * M4: a DKIM key lets receivers check that a letter really comes from the domain. Its name (the selector)
 * is set by the mail service and cannot be listed from DNS, so only common selectors are tried:
 * finding none is not proof there is no key, and the check is skipped rather than failed.
 */
class DkimCheck implements DnsRule
{
    public function run(Snapshot $site): Check
    {
        if ($site->hasNullMx() && $site->spf === []) {
            return Check::skip('M4', 10, 'no_mail');
        }

        return $site->dkimSelectors === []
            ? Check::skip('M4', 10, 'not_found')
            : Check::pass('M4', 10, 'ok', evidence: array_map(fn (string $selector): string => "{$selector}._domainkey.{$site->domain}", $site->dkimSelectors));
    }
}
