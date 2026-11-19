<?php

return [
    'form' => [
        'label' => 'Domain oder Website-Adresse',
        'button' => 'Prüfen',
        'hint' => 'Kostenlos · ohne Anmeldung · bis zu :count Prüfungen pro Stunde',
    ],
    'loading' => [
        'domain' => 'Lese die Registrierung der Domain',
        'mail' => 'Lese die DNS- und Mail-Einträge',
        'checks' => 'Prüfe SPF, DKIM und DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Geben Sie eine Domain ein, zum Beispiel example.com',
        'no_domain' => 'Diese Domain gibt es im DNS nicht. Prüfen Sie die Schreibweise',
    ],
    'verdicts' => [
        'ready' => 'Domain und Mail sind gut eingerichtet',
        'almost' => 'Domain und Mail sind fast gut eingerichtet',
        'partly' => 'Domain und Mail müssen nachgebessert werden',
        'not_ready' => 'Domain und Mail sind schlecht eingerichtet',
    ],
    'facts' => [
        'expires' => 'Registriert bis',
        'nameservers' => 'Nameserver',
        'mail' => 'Mailserver',
    ],
    'groups' => [
        'D' => ['title' => 'Die Domain', 'text' => 'Registrierung, Einträge für die Website und die Nameserver'],
        'M' => ['title' => 'Die E-Mail', 'text' => 'Wovon es abhängt, ob die Mails der Domain im Posteingang oder im Spam landen'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Läuft die Domain bald ab?',
            'text' => 'Mindestens 45 Tage Registrierung übrig',
            'fix' => 'Verlängern Sie die Domain beim Registrar und schalten Sie die automatische Verlängerung mit einer Karte ein, die nicht abläuft. Eine abgelaufene Domain legt Website und Mail sofort lahm, und jemand anderes kann sie registrieren.',
            'notes' => [
                'ok' => 'Registriert bis :date, noch :days Tage.',
                'soon' => 'Nur noch :days Tage, bis :date. Stellen Sie sicher, dass sie automatisch verlängert wird.',
                'urgent' => 'Nur noch :days Tage, bis :date. Verlängern Sie sie jetzt.',
                'expired' => 'Die Registrierung ist am :date abgelaufen.',
                'unknown' => 'Die Registry zeigt kein Ablaufdatum.',
                'no_rdap' => 'Die Registry dieser Zone veröffentlicht keine Registrierungsdaten über RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Öffnet sich die Website mit und ohne www?',
            'text' => 'Einträge für die Domain und für www',
            'fix' => 'Lassen Sie Domain und www auf den Server zeigen: einen A-Eintrag für die Domain und einen CNAME für www. Richten Sie dann eine Weiterleitung von einer auf die andere ein, damit die Website eine Hauptadresse hat.',
            'notes' => [
                'ok' => 'Domain und www zeigen auf einen Server.',
                'no_www' => 'www.:domain hat keinen Eintrag: Wer www eingibt, bekommt einen Fehler.',
                'www_only' => ':domain ohne www hat keinen Eintrag: Wer die kurze Adresse eingibt, bekommt einen Fehler.',
                'none' => 'Weder :domain noch www zeigen auf einen Server: Die Website öffnet sich nicht.',
            ],
        ],
        'D3' => [
            'title' => 'Antwortet die Domain, wenn ein DNS-Server ausfällt?',
            'text' => 'Mindestens 2 Nameserver in verschiedenen Netzen',
            'fix' => 'Nutzen Sie mindestens zwei Nameserver in verschiedenen Netzen. Die DNS-Dienste von Registraren und Cloudflare bieten das von Haus aus; die Nameserver ändern Sie im Panel Ihres Registrars.',
            'notes' => [
                'ok' => 'Nameserver: :count, in verschiedenen Netzen.',
                'single' => 'Nameserver: :count. Fällt er aus, funktionieren Website und Mail nicht mehr.',
                'same_network' => 'Nameserver: :count, aber alle im selben Netz: Ein Ausfall legt alle lahm.',
            ],
        ],
        'D4' => [
            'title' => 'Ist eingeschränkt, wer SSL für die Domain ausstellen darf?',
            'text' => 'Ein CAA-Eintrag',
            'fix' => 'Fügen Sie einen CAA-Eintrag mit der Stelle hinzu, die Ihre Zertifikate ausstellt, z. B. letsencrypt.org. Dann verweigern andere Stellen ein Zertifikat für Ihre Domain.',
            'notes' => [
                'ok' => 'CAA nennt die erlaubten Zertifizierungsstellen.',
                'none' => 'Es gibt keinen CAA-Eintrag: Jede Stelle darf ein Zertifikat für die Domain ausstellen.',
            ],
        ],
        'M1' => [
            'title' => 'Empfängt die Domain Mail?',
            'text' => 'MX-Einträge, die auf echte Server zeigen',
            'fix' => 'Fügen Sie die MX-Einträge hinzu, die Ihr Mail-Dienst vorgibt (Google Workspace, Microsoft 365, Zoho, Ihr Hosting). Empfängt die Domain nie Mail, fügen Sie einen „Null-MX“-Eintrag „0 .“ hinzu, damit Absender es sofort wissen.',
            'notes' => [
                'ok' => 'Mailserver: :count, alle existieren.',
                'null' => 'Die Domain gibt an, keine Mail zu empfangen (Null-MX).',
                'none' => 'Es gibt keine MX-Einträge: Mails an Adressen der Domain kommen nicht an.',
                'broken' => 'Diese Mailserver gibt es im DNS nicht: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Ist festgelegt, welche Server die Mail der Domain senden?',
            'text' => 'Ein SPF-Eintrag, der mit ~all oder -all endet',
            'fix' => 'Behalten Sie einen TXT-Eintrag, der mit v=spf1 beginnt, jeden Dienst einschließt, der Mail für Sie sendet (Mail-Dienst, Website, Newsletter), und mit ~all endet. Sendet die Domain keine Mail, nutzen Sie „v=spf1 -all“.',
            'notes' => [
                'ok' => 'SPF ist gesetzt, :count DNS-Abfragen von 10 erlaubten.',
                'none' => 'Es gibt keinen SPF-Eintrag: Jeder kann Mails in Ihrem Namen senden, und echte landen öfter im Spam.',
                'multiple' => 'Es gibt :count SPF-Einträge: Empfänger ignorieren dann alle. Führen Sie sie zu einem zusammen.',
                'pass_all' => 'SPF endet mit +all: Das erlaubt jedem Server der Welt, Mail für die Domain zu senden.',
                'too_many_lookups' => 'SPF braucht :count DNS-Abfragen, mehr als die erlaubten 10: Empfänger halten es für defekt.',
                'neutral' => 'SPF endet mit ?all: Es sagt nichts über andere Server, was fast so ist wie gar kein SPF.',
                'no_all' => 'SPF endet nicht mit ~all oder -all: Andere Server sind nicht ausgeschlossen.',
            ],
        ],
        'M3' => [
            'title' => 'Wissen Empfänger, was sie mit gefälschten Mails tun sollen?',
            'text' => 'Eine DMARC-Richtlinie mit Berichten',
            'fix' => 'Fügen Sie bei _dmarc einen TXT-Eintrag mit der Richtlinie p=quarantine (oder p=reject) und einer Adresse für Berichte in rua hinzu. Beginnen Sie ein paar Wochen mit p=none, wenn Sie nicht sicher sind, dass alle Ihre Absender SPF und DKIM bestehen.',
            'notes' => [
                'ok' => 'DMARC ist mit der Richtlinie :policy und Berichten gesetzt.',
                'none' => 'Es gibt kein DMARC: Gmail und Yahoo können Mails der Domain ablehnen oder markieren, und Fälschungen kommen durch.',
                'multiple' => 'Es gibt :count DMARC-Einträge: Empfänger ignorieren dann alle.',
                'invalid' => 'Der DMARC-Eintrag hat keine gültige Richtlinie p=.',
                'monitor' => 'Die Richtlinie ist p=none: Fälschungen werden nur gemeldet, nicht gestoppt.',
                'no_reports' => 'Die Richtlinie ist :policy, aber es gibt keine rua-Adresse: Sie erfahren nicht, wer Mails in Ihrem Namen sendet.',
            ],
        ],
        'M4' => [
            'title' => 'Sind die Mails signiert?',
            'text' => 'Ein DKIM-Schlüssel',
            'fix' => 'Schalten Sie DKIM in Ihrem Mail-Dienst ein und fügen Sie den Eintrag, den er vorgibt, im DNS hinzu. Eine signierte Mail beweist, dass sie wirklich von Ihrer Domain kommt.',
            'notes' => [
                'ok' => 'Ein DKIM-Schlüssel wurde gefunden.',
                'not_found' => 'Unter den üblichen Namen wurde kein Schlüssel gefunden. Den Namen legt Ihr Mail-Dienst fest, der Schlüssel kann also trotzdem existieren: Prüfen Sie dessen Einstellungen.',
                'no_mail' => 'Die Domain empfängt und sendet keine Mail.',
            ],
        ],
    ],
];
