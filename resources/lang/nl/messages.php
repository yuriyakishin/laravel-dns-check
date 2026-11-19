<?php

return [
    'form' => [
        'label' => 'Domein of adres van de website',
        'button' => 'Controleren',
        'hint' => 'Gratis · zonder registratie · tot :count controles per uur',
    ],
    'loading' => [
        'domain' => 'De registratie van het domein lezen',
        'mail' => 'De DNS- en mailrecords lezen',
        'checks' => 'SPF, DKIM en DMARC controleren',
    ],
    'errors' => [
        'invalid_url' => 'Voer een domein in, bijvoorbeeld example.com',
        'no_domain' => 'Dit domein bestaat niet in de DNS. Controleer de spelling',
    ],
    'verdicts' => [
        'ready' => 'Domein en mail zijn goed ingesteld',
        'almost' => 'Domein en mail zijn bijna goed ingesteld',
        'partly' => 'Domein en mail moeten beter worden ingesteld',
        'not_ready' => 'Domein en mail zijn slecht ingesteld',
    ],
    'facts' => [
        'expires' => 'Geregistreerd tot',
        'nameservers' => 'Nameservers',
        'mail' => 'Mailservers',
    ],
    'groups' => [
        'D' => ['title' => 'Het domein', 'text' => 'De registratie, de records voor de website en de nameservers'],
        'M' => ['title' => 'De e-mail', 'text' => 'Waar het van afhangt of de mails van het domein in de inbox of in spam belanden'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Verloopt het domein binnenkort?',
            'text' => 'Nog minstens 45 dagen registratie',
            'fix' => 'Verleng het domein bij uw registrar en zet automatisch verlengen aan met een kaart die niet verloopt. Een verlopen domein legt website en mail meteen plat, en iemand anders kan het registreren.',
            'notes' => [
                'ok' => 'Geregistreerd tot :date, nog :days dagen.',
                'soon' => 'Nog maar :days dagen, tot :date. Zorg dat het automatisch wordt verlengd.',
                'urgent' => 'Nog maar :days dagen, tot :date. Verleng het nu.',
                'expired' => 'De registratie is op :date afgelopen.',
                'unknown' => 'Het register toont geen vervaldatum.',
                'no_rdap' => 'Het register van deze zone publiceert geen registratiegegevens via RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Opent de website met en zonder www?',
            'text' => 'Records voor het domein en voor www',
            'fix' => 'Laat zowel het domein als www naar de server wijzen: een A-record voor het domein en een CNAME voor www. Stel daarna een doorverwijzing van het ene naar het andere in, zodat de website één hoofdadres heeft.',
            'notes' => [
                'ok' => 'Het domein en www wijzen naar een server.',
                'no_www' => 'www.:domain heeft geen record: wie www typt, krijgt een fout.',
                'www_only' => ':domain zonder www heeft geen record: wie het korte adres typt, krijgt een fout.',
                'none' => 'Noch :domain noch www wijzen naar een server: de website opent niet.',
            ],
        ],
        'D3' => [
            'title' => 'Antwoordt het domein als een DNS-server uitvalt?',
            'text' => 'Minstens 2 nameservers in verschillende netwerken',
            'fix' => 'Gebruik minstens twee nameservers in verschillende netwerken. De DNS van registrars en Cloudflare doet dat standaard; de nameservers wijzigt u in het paneel van uw registrar.',
            'notes' => [
                'ok' => 'Nameservers: :count, in verschillende netwerken.',
                'single' => 'Nameservers: :count. Valt die uit, dan werken website en mail niet meer.',
                'same_network' => 'Nameservers: :count, maar allemaal in één netwerk: één storing legt ze allemaal plat.',
            ],
        ],
        'D4' => [
            'title' => 'Is beperkt wie SSL voor het domein mag uitgeven?',
            'text' => 'Een CAA-record',
            'fix' => 'Voeg een CAA-record toe met de instantie die uw certificaten uitgeeft, bijvoorbeeld letsencrypt.org. Dan weigeren andere instanties een certificaat voor uw domein uit te geven.',
            'notes' => [
                'ok' => 'CAA noemt de toegestane instanties.',
                'none' => 'Er is geen CAA-record: elke instantie mag een certificaat voor het domein uitgeven.',
            ],
        ],
        'M1' => [
            'title' => 'Ontvangt het domein mail?',
            'text' => 'MX-records die naar echte servers wijzen',
            'fix' => 'Voeg de MX-records toe die uw maildienst geeft (Google Workspace, Microsoft 365, Zoho, uw hosting). Ontvangt het domein nooit mail, voeg dan een „null MX”-record „0 .” toe, zodat afzenders het meteen weten.',
            'notes' => [
                'ok' => 'Mailservers: :count, ze bestaan allemaal.',
                'null' => 'Het domein geeft aan geen mail te ontvangen (null MX).',
                'none' => 'Er zijn geen MX-records: mails naar adressen op het domein komen niet aan.',
                'broken' => 'Deze mailservers bestaan niet in de DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Is vastgelegd welke servers de mail van het domein versturen?',
            'text' => 'Eén SPF-record, eindigend op ~all of -all',
            'fix' => 'Houd één TXT-record dat begint met v=spf1, elke dienst bevat die mail voor u verstuurt (maildienst, website, nieuwsbrieven) en eindigt op ~all. Verstuurt het domein geen mail, gebruik dan „v=spf1 -all”.',
            'notes' => [
                'ok' => 'SPF is ingesteld, :count DNS-zoekopdrachten van de 10 toegestane.',
                'none' => 'Er is geen SPF-record: iedereen kan mails uit uw naam versturen, en echte belanden vaker in spam.',
                'multiple' => 'Er zijn :count SPF-records: ontvangers negeren ze dan allemaal. Voeg ze samen tot één.',
                'pass_all' => 'SPF eindigt op +all: het staat elke server ter wereld toe mail voor het domein te versturen.',
                'too_many_lookups' => 'SPF heeft :count DNS-zoekopdrachten nodig, meer dan de 10 toegestane: ontvangers zien het als kapot.',
                'neutral' => 'SPF eindigt op ?all: het zegt niets over andere servers, wat bijna hetzelfde is als geen SPF.',
                'no_all' => 'SPF eindigt niet op ~all of -all: andere servers zijn niet uitgesloten.',
            ],
        ],
        'M3' => [
            'title' => 'Weten ontvangers wat ze met valse mails moeten doen?',
            'text' => 'Een DMARC-beleid met rapporten',
            'fix' => 'Voeg bij _dmarc een TXT-record toe met het beleid p=quarantine (of p=reject) en een adres voor rapporten in rua. Begin een paar weken met p=none als u niet zeker weet of al uw afzenders SPF en DKIM halen.',
            'notes' => [
                'ok' => 'DMARC is ingesteld met het beleid :policy en rapporten.',
                'none' => 'Er is geen DMARC: Gmail en Yahoo kunnen mails van het domein weigeren of markeren, en vervalsingen komen erdoor.',
                'multiple' => 'Er zijn :count DMARC-records: ontvangers negeren ze dan allemaal.',
                'invalid' => 'Het DMARC-record heeft geen geldig beleid p=.',
                'monitor' => 'Het beleid is p=none: vervalsingen worden alleen gemeld, niet tegengehouden.',
                'no_reports' => 'Het beleid is :policy, maar er is geen rua-adres: u komt niet te weten wie mails uit uw naam verstuurt.',
            ],
        ],
        'M4' => [
            'title' => 'Zijn de mails ondertekend?',
            'text' => 'Een DKIM-sleutel',
            'fix' => 'Zet DKIM aan in uw maildienst en voeg het record dat die geeft toe aan de DNS. Een ondertekende mail bewijst dat hij echt van uw domein komt.',
            'notes' => [
                'ok' => 'Er is een DKIM-sleutel gevonden.',
                'not_found' => 'Onder de gangbare namen is geen sleutel gevonden. De naam wordt door uw maildienst gekozen, dus de sleutel kan toch bestaan: controleer de instellingen daarvan.',
                'no_mail' => 'Het domein ontvangt en verstuurt geen mail.',
            ],
        ],
    ],
];
