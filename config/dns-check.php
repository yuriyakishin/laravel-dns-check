<?php

return [
    'enabled' => env('DNS_CHECK_ENABLED', true),

    /* Version of the scoring rules shown under the score; change it when the checks or points change. */
    'ruleset' => '2026-11-14',

    /* DKIM keys have no fixed name: these selectors of popular mail services are tried one by one. */
    'dkim_selectors' => ['google', 'selector1', 'selector2', 'default', 'dkim', 'mail', 'k1', 'k2', 's1', 's2', 'smtp', 'mandrill', 'zoho', 'mxvault', 'sendgrid', 'resend', 'cf2024-1'],

    /* The IANA list of RDAP servers by domain zone, kept for a day. */
    'rdap_bootstrap' => 'https://data.iana.org/rdap/dns.json',

    'rate_limit' => [
        'per_hour' => (int) env('DNS_CHECK_PER_HOUR', 10),
    ],

    /* DNS changes take minutes to spread, so a check is kept a little longer than the others. */
    'cache' => [
        'ttl' => 300,
    ],
];
