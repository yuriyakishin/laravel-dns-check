<?php

return [
    'form' => [
        'label' => 'Doména nebo adresa webu',
        'button' => 'Zkontrolovat',
        'hint' => 'Zdarma · bez registrace · až :count kontrol za hodinu',
    ],
    'loading' => [
        'domain' => 'Čtu registraci domény',
        'mail' => 'Čtu záznamy DNS a pošty',
        'checks' => 'Kontroluji SPF, DKIM a DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Zadejte doménu, například example.com',
        'no_domain' => 'Tato doména v DNS neexistuje. Zkontrolujte, jak je napsaná',
    ],
    'verdicts' => [
        'ready' => 'Doména a pošta jsou nastavené dobře',
        'almost' => 'Doména a pošta jsou nastavené téměř dobře',
        'partly' => 'Doménu a poštu je třeba donastavit',
        'not_ready' => 'Doména a pošta jsou nastavené špatně',
    ],
    'facts' => [
        'expires' => 'Registrováno do',
        'nameservers' => 'DNS servery',
        'mail' => 'Poštovní servery',
    ],
    'groups' => [
        'D' => ['title' => 'Doména', 'text' => 'Registrace, záznamy pro web a DNS servery'],
        'M' => ['title' => 'Pošta', 'text' => 'Na čem záleží, zda e-maily domény skončí v doručené poště, a ne ve spamu'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Vyprší doména brzy?',
            'text' => 'Zbývá alespoň 45 dní registrace',
            'fix' => 'Prodlužte doménu u registrátora a zapněte automatické prodlužování s kartou, které nevyprší platnost. Propadlá doména okamžitě vypne web i poštu a může ji zaregistrovat někdo jiný.',
            'notes' => [
                'ok' => 'Registrováno do :date, zbývá dní: :days.',
                'soon' => 'Zbývá jen dní: :days, do :date. Ověřte, že se prodlouží automaticky.',
                'urgent' => 'Zbývá jen dní: :days, do :date. Prodlužte ji hned.',
                'expired' => 'Registrace skončila :date.',
                'unknown' => 'Registr neukazuje datum vypršení.',
                'no_rdap' => 'Registr této zóny nezveřejňuje údaje o registraci přes RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Otevírá se web s www i bez?',
            'text' => 'Záznamy pro doménu i pro www',
            'fix' => 'Nasměrujte na server doménu i www: záznam A pro doménu a CNAME pro www. Pak nastavte přesměrování z jedné na druhou, aby měl web jednu hlavní adresu.',
            'notes' => [
                'ok' => 'Doména i www vedou na server.',
                'no_www' => 'www.:domain nemá záznam: kdo napíše www, uvidí chybu.',
                'www_only' => ':domain bez www nemá záznam: kdo napíše krátkou adresu, uvidí chybu.',
                'none' => 'Ani :domain, ani www nevedou na server: web se neotevírá.',
            ],
        ],
        'D3' => [
            'title' => 'Bude doména odpovídat, když spadne DNS server?',
            'text' => 'Alespoň 2 DNS servery v různých sítích',
            'fix' => 'Používejte alespoň dva DNS servery v různých sítích. DNS registrátorů a Cloudflare to dávají automaticky; DNS servery se mění v panelu registrátora.',
            'notes' => [
                'ok' => 'DNS serverů: :count, v různých sítích.',
                'single' => 'DNS serverů: :count. Když spadne, přestane fungovat web i pošta.',
                'same_network' => 'DNS serverů: :count, ale všechny v jedné síti: jedna porucha vyřadí všechny.',
            ],
        ],
        'D4' => [
            'title' => 'Je omezeno, kdo smí pro doménu vydat SSL?',
            'text' => 'Záznam CAA',
            'fix' => 'Přidejte záznam CAA s autoritou, která vydává vaše certifikáty, např. letsencrypt.org. Ostatní autority pak vydání certifikátu pro vaši doménu odmítnou.',
            'notes' => [
                'ok' => 'CAA uvádí povolené certifikační autority.',
                'none' => 'Záznam CAA chybí: certifikát pro doménu může vydat jakákoli autorita.',
            ],
        ],
        'M1' => [
            'title' => 'Přijímá doména poštu?',
            'text' => 'Záznamy MX vedoucí na skutečné servery',
            'fix' => 'Přidejte záznamy MX, které vám dá poštovní služba (Google Workspace, Microsoft 365, Zoho, váš hosting). Pokud doména nikdy poštu nepřijímá, přidejte záznam „null MX“ „0 .“, aby to odesílatelé hned věděli.',
            'notes' => [
                'ok' => 'Poštovních serverů: :count, všechny existují.',
                'null' => 'Doména uvádí, že poštu nepřijímá (null MX).',
                'none' => 'Záznamy MX chybí: e-maily na adresy domény nedojdou.',
                'broken' => 'Tyto poštovní servery v DNS neexistují: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Je určeno, které servery posílají poštu domény?',
            'text' => 'Jeden záznam SPF, končící ~all nebo -all',
            'fix' => 'Ponechte jeden záznam TXT začínající v=spf1, který zahrnuje každou službu posílající poštu za vás (pošta, web, newslettery) a končí ~all. Pokud doména poštu neposílá, použijte „v=spf1 -all“.',
            'notes' => [
                'ok' => 'SPF je nastavený, DNS dotazů: :count z 10 povolených.',
                'none' => 'Záznam SPF chybí: kdokoli může posílat e-maily vaším jménem a ty skutečné častěji končí ve spamu.',
                'multiple' => 'Záznamů SPF: :count. Příjemci pak ignorují všechny. Spojte je do jednoho.',
                'pass_all' => 'SPF končí +all: povoluje kterémukoli serveru na světě posílat poštu za doménu.',
                'too_many_lookups' => 'SPF potřebuje DNS dotazů: :count, víc než 10 povolených: příjemci ho považují za rozbitý.',
                'neutral' => 'SPF končí ?all: o jiných serverech nic neříká, což je skoro jako žádné SPF.',
                'no_all' => 'SPF nekončí ~all ani -all: jiné servery nejsou vyloučené.',
            ],
        ],
        'M3' => [
            'title' => 'Vědí příjemci, co dělat s podvrženými e-maily?',
            'text' => 'Zásada DMARC s reporty',
            'fix' => 'Přidejte záznam TXT na _dmarc se zásadou p=quarantine (nebo p=reject) a adresou pro reporty v rua. Začněte na pár týdnů s p=none, pokud si nejste jistí, že všichni vaši odesílatelé projdou SPF a DKIM.',
            'notes' => [
                'ok' => 'DMARC je nastavený se zásadou :policy a reporty.',
                'none' => 'DMARC chybí: Gmail a Yahoo mohou e-maily domény odmítat nebo označovat a podvrhy projdou.',
                'multiple' => 'Záznamů DMARC: :count. Příjemci pak ignorují všechny.',
                'invalid' => 'Záznam DMARC nemá platnou zásadu p=.',
                'monitor' => 'Zásada je p=none: podvrhy se jen hlásí, nezastavují.',
                'no_reports' => 'Zásada je :policy, ale chybí adresa rua: nedozvíte se, kdo posílá e-maily vaším jménem.',
            ],
        ],
        'M4' => [
            'title' => 'Jsou e-maily podepsané?',
            'text' => 'Klíč DKIM',
            'fix' => 'Zapněte DKIM ve své poštovní službě a přidejte do DNS záznam, který vám dá. Podepsaný e-mail dokazuje, že opravdu pochází z vaší domény.',
            'notes' => [
                'ok' => 'Klíč DKIM byl nalezen.',
                'not_found' => 'Pod běžnými názvy se klíč nenašel. Název určuje vaše poštovní služba, takže klíč přesto může existovat: zkontrolujte její nastavení.',
                'no_mail' => 'Doména poštu nepřijímá ani neposílá.',
            ],
        ],
    ],
];
