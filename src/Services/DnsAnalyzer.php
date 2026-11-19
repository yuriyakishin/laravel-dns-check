<?php

namespace Yu\DnsCheck\Services;

use Yu\DnsCheck\Checks;
use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\ChecklistReport;

/**
 * Reads the DNS records and the registration of a domain and runs the checks.
 */
class DnsAnalyzer
{
    /**
     * Checks by group, in the order of the report: D — the domain, M — the mail.
     * The letter codes are internal keys of the translations, the report never shows them.
     */
    public const array GROUPS = [
        'D' => [Checks\ExpiryCheck::class, Checks\WebRecordsCheck::class, Checks\NameserversCheck::class, Checks\CaaCheck::class],
        'M' => [Checks\MxCheck::class, Checks\SpfCheck::class, Checks\DmarcCheck::class, Checks\DkimCheck::class],
    ];

    public function __construct(private SnapshotCollector $collector) {}

    /**
     * The checklist report with the domain facts under the key "domain".
     *
     * @return array<string, mixed>
     */
    public function analyze(string $url, string $locale): array
    {
        $site = $this->collector->collect($url);

        return [...$this->report($site, $locale)->toArray(), 'domain' => $site->toArray()];
    }

    public function report(Snapshot $site, string $locale): ChecklistReport
    {
        $groups = [];
        foreach (self::GROUPS as $group => $checks) {
            $groups[$group] = array_map(fn (string $check) => app($check)->run($site), $checks);
        }

        return new ChecklistReport(
            url: $site->domain,
            locale: $locale,
            checkedAt: now()->toIso8601String(),
            ruleset: (string) config('dns-check.ruleset'),
            groups: $groups,
        );
    }
}
