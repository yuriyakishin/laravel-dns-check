<?php

return [
    'form' => [
        'label' => 'Domena lub adres strony',
        'button' => 'Sprawdź',
        'hint' => 'Za darmo · bez rejestracji · do :count sprawdzeń na godzinę',
    ],
    'loading' => [
        'domain' => 'Odczytuję rejestrację domeny',
        'mail' => 'Odczytuję rekordy DNS i poczty',
        'checks' => 'Sprawdzam SPF, DKIM i DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Wpisz domenę, na przykład example.com',
        'no_domain' => 'Takiej domeny nie ma w DNS. Sprawdź pisownię',
    ],
    'verdicts' => [
        'ready' => 'Domena i poczta są dobrze skonfigurowane',
        'almost' => 'Domena i poczta są prawie dobrze skonfigurowane',
        'partly' => 'Domenę i pocztę trzeba poprawić',
        'not_ready' => 'Domena i poczta są źle skonfigurowane',
    ],
    'facts' => [
        'expires' => 'Zarejestrowana do',
        'nameservers' => 'Serwery DNS',
        'mail' => 'Serwery poczty',
    ],
    'groups' => [
        'D' => ['title' => 'Domena', 'text' => 'Rejestracja, rekordy dla strony i serwery DNS'],
        'M' => ['title' => 'Poczta', 'text' => 'Od czego zależy, czy listy z domeny trafią do odebranych, a nie do spamu'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Czy domena wkrótce wygaśnie?',
            'text' => 'Zostało co najmniej 45 dni rejestracji',
            'fix' => 'Przedłuż domenę u rejestratora i włącz automatyczne odnawianie z kartą, która nie wygaśnie. Wygasła domena od razu wyłącza stronę i pocztę, a ktoś inny może ją zarejestrować.',
            'notes' => [
                'ok' => 'Zarejestrowana do :date, zostało dni: :days.',
                'soon' => 'Zostało tylko dni: :days, do :date. Upewnij się, że odnowi się automatycznie.',
                'urgent' => 'Zostało tylko dni: :days, do :date. Przedłuż ją teraz.',
                'expired' => 'Rejestracja skończyła się :date.',
                'unknown' => 'Rejestr nie pokazuje daty wygaśnięcia.',
                'no_rdap' => 'Rejestr tej strefy nie publikuje danych rejestracji przez RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Czy strona otwiera się z www i bez?',
            'text' => 'Rekordy dla domeny i dla www',
            'fix' => 'Skieruj na serwer i domenę, i www: rekord A dla domeny i CNAME dla www. Potem ustaw przekierowanie z jednego na drugi, żeby strona miała jeden główny adres.',
            'notes' => [
                'ok' => 'Domena i www prowadzą do serwera.',
                'no_www' => 'www.:domain nie ma rekordu: kto wpisze www, zobaczy błąd.',
                'www_only' => ':domain bez www nie ma rekordu: kto wpisze krótki adres, zobaczy błąd.',
                'none' => 'Ani :domain, ani www nie prowadzą do serwera: strona się nie otwiera.',
            ],
        ],
        'D3' => [
            'title' => 'Czy domena zadziała, gdy padnie serwer DNS?',
            'text' => 'Co najmniej 2 serwery DNS w różnych sieciach',
            'fix' => 'Używaj co najmniej dwóch serwerów DNS w różnych sieciach. DNS rejestratorów i Cloudflare zapewniają to domyślnie; serwery DNS zmienia się w panelu rejestratora.',
            'notes' => [
                'ok' => 'Serwery DNS: :count, w różnych sieciach.',
                'single' => 'Serwery DNS: :count. Jeśli padnie, przestaną działać strona i poczta.',
                'same_network' => 'Serwery DNS: :count, ale wszystkie w jednej sieci: jedna awaria wyłączy je wszystkie.',
            ],
        ],
        'D4' => [
            'title' => 'Czy ograniczono, kto może wystawić SSL dla domeny?',
            'text' => 'Rekord CAA',
            'fix' => 'Dodaj rekord CAA z urzędem, który wystawia twoje certyfikaty, np. letsencrypt.org. Wtedy inne urzędy odmówią wystawienia certyfikatu dla twojej domeny.',
            'notes' => [
                'ok' => 'CAA wskazuje dozwolone urzędy certyfikacji.',
                'none' => 'Nie ma rekordu CAA: certyfikat dla domeny może wystawić każdy urząd.',
            ],
        ],
        'M1' => [
            'title' => 'Czy domena odbiera pocztę?',
            'text' => 'Rekordy MX prowadzące do prawdziwych serwerów',
            'fix' => 'Dodaj rekordy MX, które podaje twój dostawca poczty (Google Workspace, Microsoft 365, Zoho, twój hosting). Jeśli domena nigdy nie odbiera poczty, dodaj rekord „null MX” „0 .”, żeby nadawcy od razu o tym wiedzieli.',
            'notes' => [
                'ok' => 'Serwery poczty: :count, wszystkie istnieją.',
                'null' => 'Domena deklaruje, że nie odbiera poczty (null MX).',
                'none' => 'Nie ma rekordów MX: listy na adresy w domenie nie dotrą.',
                'broken' => 'Tych serwerów poczty nie ma w DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Czy wskazano, które serwery wysyłają pocztę domeny?',
            'text' => 'Jeden rekord SPF, kończący się na ~all lub -all',
            'fix' => 'Zostaw jeden rekord TXT zaczynający się od v=spf1, który obejmuje każdą usługę wysyłającą pocztę w twoim imieniu (poczta, strona, newslettery) i kończy się na ~all. Jeśli domena nie wysyła poczty, użyj „v=spf1 -all”.',
            'notes' => [
                'ok' => 'SPF jest ustawiony, zapytań DNS: :count z 10 dozwolonych.',
                'none' => 'Nie ma rekordu SPF: każdy może wysyłać listy w twoim imieniu, a prawdziwe częściej trafiają do spamu.',
                'multiple' => 'Rekordów SPF: :count. Odbiorcy ignorują wtedy wszystkie. Połącz je w jeden.',
                'pass_all' => 'SPF kończy się na +all: pozwala każdemu serwerowi na świecie wysyłać pocztę w imieniu domeny.',
                'too_many_lookups' => 'SPF wymaga zapytań DNS: :count, więcej niż 10 dozwolonych: odbiorcy uznają go za uszkodzony.',
                'neutral' => 'SPF kończy się na ?all: nic nie mówi o innych serwerach, co jest prawie tym samym co brak SPF.',
                'no_all' => 'SPF nie kończy się na ~all ani -all: inne serwery nie są wykluczone.',
            ],
        ],
        'M3' => [
            'title' => 'Czy odbiorcy wiedzą, co robić z fałszywymi listami?',
            'text' => 'Polityka DMARC z raportami',
            'fix' => 'Dodaj rekord TXT na _dmarc z polityką p=quarantine (lub p=reject) i adresem na raporty w rua. Zacznij od p=none na kilka tygodni, jeśli nie masz pewności, że wszyscy twoi nadawcy przechodzą SPF i DKIM.',
            'notes' => [
                'ok' => 'DMARC jest ustawiony z polityką :policy i raportami.',
                'none' => 'Nie ma DMARC: Gmail i Yahoo mogą odrzucać lub oznaczać listy z domeny, a fałszywki przechodzą.',
                'multiple' => 'Rekordów DMARC: :count. Odbiorcy ignorują wtedy wszystkie.',
                'invalid' => 'Rekord DMARC nie ma poprawnej polityki p=.',
                'monitor' => 'Polityka to p=none: fałszywki są tylko zgłaszane, a nie zatrzymywane.',
                'no_reports' => 'Polityka to :policy, ale nie ma adresu rua: nie dowiesz się, kto wysyła listy w twoim imieniu.',
            ],
        ],
        'M4' => [
            'title' => 'Czy listy są podpisane?',
            'text' => 'Klucz DKIM',
            'fix' => 'Włącz DKIM u swojego dostawcy poczty i dodaj do DNS rekord, który poda. Podpisany list dowodzi, że naprawdę pochodzi z twojej domeny.',
            'notes' => [
                'ok' => 'Znaleziono klucz DKIM.',
                'not_found' => 'Pod popularnymi nazwami nie znaleziono klucza. Nazwę ustala twój dostawca poczty, więc klucz może istnieć: sprawdź jego ustawienia.',
                'no_mail' => 'Domena nie odbiera ani nie wysyła poczty.',
            ],
        ],
    ],
];
