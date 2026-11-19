<?php

return [
    'form' => [
        'label' => 'Domain or site address',
        'button' => 'Check',
        'hint' => 'Free · no sign-up · up to :count checks per hour',
    ],
    'loading' => [
        'domain' => 'Reading the domain registration',
        'mail' => 'Reading the DNS and mail records',
        'checks' => 'Checking SPF, DKIM and DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Enter a domain, for example example.com',
        'no_domain' => 'This domain was not found in DNS. Check the spelling',
    ],
    'verdicts' => [
        'ready' => 'The domain and the mail are set up well',
        'almost' => 'The domain and the mail are almost set up',
        'partly' => 'The domain and the mail need work',
        'not_ready' => 'The domain and the mail are poorly set up',
    ],
    'facts' => [
        'expires' => 'Registered until',
        'nameservers' => 'Name servers',
        'mail' => 'Mail servers',
    ],
    'groups' => [
        'D' => ['title' => 'The domain', 'text' => 'Registration, records for the site and the name servers'],
        'M' => ['title' => 'The email', 'text' => 'What decides whether the domain\'s letters land in the inbox or in spam'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Will the domain expire soon?',
            'text' => 'At least 45 days of registration left',
            'fix' => 'Renew the domain at your registrar and turn on automatic renewal with a card that will not expire. A lapsed domain takes the site and the mail down at once, and someone else may register it.',
            'notes' => [
                'ok' => 'Registered until :date, :days days left.',
                'soon' => 'Only :days days left, until :date. Make sure it renews automatically.',
                'urgent' => 'Only :days days left, until :date. Renew it now.',
                'expired' => 'The registration ended on :date.',
                'unknown' => 'The registry does not show the expiry date.',
                'no_rdap' => 'The registry of this zone does not publish registration data over RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Does the site open with and without www?',
            'text' => 'Records for the domain and for www',
            'fix' => 'Point both the domain and www to the server: an A record for the domain and a CNAME for www. Then set up a redirect from one to the other so the site has one main address.',
            'notes' => [
                'ok' => 'Both the domain and www point to a server.',
                'no_www' => 'www.:domain has no record: people who type www get an error.',
                'www_only' => ':domain without www has no record: people who type the short address get an error.',
                'none' => 'Neither :domain nor www point to a server: the site does not open.',
            ],
        ],
        'D3' => [
            'title' => 'Will the domain answer if a DNS server goes down?',
            'text' => 'At least 2 name servers in different networks',
            'fix' => 'Use at least two name servers in different networks. DNS services of registrars and Cloudflare give this by default; change the name servers in your registrar\'s panel.',
            'notes' => [
                'ok' => 'Name servers: :count, in different networks.',
                'single' => 'Name servers: :count. If it goes down, the site and the mail stop working.',
                'same_network' => 'Name servers: :count, but all in one network: one failure takes them all down.',
            ],
        ],
        'D4' => [
            'title' => 'Is it limited who can issue SSL for the domain?',
            'text' => 'A CAA record',
            'fix' => 'Add a CAA record naming the authority that issues your certificates, e.g. letsencrypt.org. Then other authorities refuse to issue a certificate for your domain.',
            'notes' => [
                'ok' => 'CAA names the allowed authorities.',
                'none' => 'There is no CAA record: any authority may issue a certificate for the domain.',
            ],
        ],
        'M1' => [
            'title' => 'Does the domain receive mail?',
            'text' => 'MX records that point to real servers',
            'fix' => 'Add the MX records your mail service gives you (Google Workspace, Microsoft 365, Zoho, your hosting). If the domain never receives mail, add a "null MX" record "0 ." so senders know it at once.',
            'notes' => [
                'ok' => 'Mail servers: :count, all of them exist.',
                'null' => 'The domain says it does not receive mail (null MX).',
                'none' => 'There are no MX records: letters to addresses on the domain will not arrive.',
                'broken' => 'These mail servers do not exist in DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Is it set which servers send the domain\'s mail?',
            'text' => 'One SPF record, ending with ~all or -all',
            'fix' => 'Keep one TXT record starting with v=spf1 that includes every service sending mail for you (the mail service, the site, newsletters) and ends with ~all. If the domain sends no mail, use "v=spf1 -all".',
            'notes' => [
                'ok' => 'SPF is set, :count DNS lookups out of 10 allowed.',
                'none' => 'There is no SPF record: anyone can send letters in your name, and real ones go to spam more often.',
                'multiple' => 'There are :count SPF records: receivers then ignore all of them. Merge them into one.',
                'pass_all' => 'SPF ends with +all: it allows every server in the world to send mail for the domain.',
                'too_many_lookups' => 'SPF needs :count DNS lookups, more than the 10 allowed: receivers treat it as broken.',
                'neutral' => 'SPF ends with ?all: it says nothing about other servers, which is almost the same as having no SPF.',
                'no_all' => 'SPF does not end with ~all or -all: other servers are not ruled out.',
            ],
        ],
        'M3' => [
            'title' => 'Do receivers know what to do with fake letters?',
            'text' => 'A DMARC policy with reports',
            'fix' => 'Add a TXT record at _dmarc with the policy p=quarantine (or p=reject) and an address for reports in rua. Start with p=none for a couple of weeks if you are not sure all your senders pass SPF and DKIM.',
            'notes' => [
                'ok' => 'DMARC is set with the policy :policy and reports.',
                'none' => 'There is no DMARC: Gmail and Yahoo may reject or mark the domain\'s letters, and fakes pass.',
                'multiple' => 'There are :count DMARC records: receivers then ignore all of them.',
                'invalid' => 'The DMARC record has no valid p= policy.',
                'monitor' => 'The policy is p=none: fakes are only reported, not stopped.',
                'no_reports' => 'The policy is :policy, but there is no rua address: you will not learn who sends letters in your name.',
            ],
        ],
        'M4' => [
            'title' => 'Are the letters signed?',
            'text' => 'A DKIM key',
            'fix' => 'Turn on DKIM in your mail service and add the record it gives you to DNS. A signed letter proves it really comes from your domain.',
            'notes' => [
                'ok' => 'A DKIM key was found.',
                'not_found' => 'No key was found under the common names. The name is set by your mail service, so the key may still exist: check its settings.',
                'no_mail' => 'The domain neither receives nor sends mail.',
            ],
        ],
    ],
];
