<?php

namespace Yu\DnsCheck\Checks;

use Yu\DnsCheck\Data\Snapshot;
use Yu\WebToolsCore\Checklist\Check;

/**
 * One rule of the report. Its title, description and advice are translations under messages.checks.{code}.
 */
interface DnsRule
{
    public function run(Snapshot $site): Check;
}
