<?php

return [
    'form' => [
        'label' => 'Dominio o indirizzo del sito',
        'button' => 'Verifica',
        'hint' => 'Gratis · senza registrazione · fino a :count verifiche all\'ora',
    ],
    'loading' => [
        'domain' => 'Leggo la registrazione del dominio',
        'mail' => 'Leggo i record DNS e di posta',
        'checks' => 'Verifico SPF, DKIM e DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Inserite un dominio, ad esempio example.com',
        'no_domain' => 'Questo dominio non esiste nel DNS. Controllate come è scritto',
    ],
    'verdicts' => [
        'ready' => 'Dominio e posta sono configurati bene',
        'almost' => 'Dominio e posta sono configurati quasi bene',
        'partly' => 'Dominio e posta vanno sistemati',
        'not_ready' => 'Dominio e posta sono configurati male',
    ],
    'facts' => [
        'expires' => 'Registrato fino al',
        'nameservers' => 'Server DNS',
        'mail' => 'Server di posta',
    ],
    'groups' => [
        'D' => ['title' => 'Il dominio', 'text' => 'La registrazione, i record del sito e i server DNS'],
        'M' => ['title' => 'La posta', 'text' => 'Da cosa dipende se le email del dominio finiscono in arrivo o nello spam'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Il dominio scade presto?',
            'text' => 'Almeno 45 giorni di registrazione rimasti',
            'fix' => 'Rinnovate il dominio presso il registrar e attivate il rinnovo automatico con una carta che non scada. Un dominio scaduto spegne subito sito e posta, e qualcun altro può registrarlo.',
            'notes' => [
                'ok' => 'Registrato fino al :date, mancano :days giorni.',
                'soon' => 'Mancano solo :days giorni, fino al :date. Controllate che si rinnovi automaticamente.',
                'urgent' => 'Mancano solo :days giorni, fino al :date. Rinnovatelo adesso.',
                'expired' => 'La registrazione è terminata il :date.',
                'unknown' => 'Il registro non mostra la data di scadenza.',
                'no_rdap' => 'Il registro di questa zona non pubblica i dati di registrazione tramite RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Il sito si apre con e senza www?',
            'text' => 'Record per il dominio e per www',
            'fix' => 'Fate puntare al server sia il dominio sia www: un record A per il dominio e un CNAME per www. Poi impostate un reindirizzamento dall\'uno all\'altro, così il sito ha un solo indirizzo principale.',
            'notes' => [
                'ok' => 'Dominio e www puntano a un server.',
                'no_www' => 'www.:domain non ha record: chi digita www vede un errore.',
                'www_only' => ':domain senza www non ha record: chi digita l\'indirizzo breve vede un errore.',
                'none' => 'Né :domain né www puntano a un server: il sito non si apre.',
            ],
        ],
        'D3' => [
            'title' => 'Il dominio risponde se un server DNS cade?',
            'text' => 'Almeno 2 server DNS in reti diverse',
            'fix' => 'Usate almeno due server DNS in reti diverse. I DNS dei registrar e di Cloudflare lo fanno già; i server DNS si cambiano nel pannello del registrar.',
            'notes' => [
                'ok' => 'Server DNS: :count, in reti diverse.',
                'single' => 'Server DNS: :count. Se cade, smettono di funzionare sito e posta.',
                'same_network' => 'Server DNS: :count, ma tutti nella stessa rete: un guasto li ferma tutti.',
            ],
        ],
        'D4' => [
            'title' => 'È limitato chi può emettere SSL per il dominio?',
            'text' => 'Un record CAA',
            'fix' => 'Aggiungete un record CAA con l\'autorità che emette i vostri certificati, ad esempio letsencrypt.org. Così le altre autorità rifiuteranno di emettere un certificato per il vostro dominio.',
            'notes' => [
                'ok' => 'CAA indica le autorità ammesse.',
                'none' => 'Non c\'è un record CAA: qualsiasi autorità può emettere un certificato per il dominio.',
            ],
        ],
        'M1' => [
            'title' => 'Il dominio riceve posta?',
            'text' => 'Record MX che portano a server reali',
            'fix' => 'Aggiungete i record MX che vi dà il servizio di posta (Google Workspace, Microsoft 365, Zoho, il vostro hosting). Se il dominio non riceve mai posta, aggiungete un record «MX nullo» «0 .» così i mittenti lo sanno subito.',
            'notes' => [
                'ok' => 'Server di posta: :count, esistono tutti.',
                'null' => 'Il dominio dichiara di non ricevere posta (MX nullo).',
                'none' => 'Non ci sono record MX: le email agli indirizzi del dominio non arriveranno.',
                'broken' => 'Questi server di posta non esistono nel DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'È indicato quali server inviano la posta del dominio?',
            'text' => 'Un solo record SPF, che finisce con ~all o -all',
            'fix' => 'Tenete un solo record TXT che inizi con v=spf1, includa ogni servizio che invia posta per voi (posta, sito, newsletter) e finisca con ~all. Se il dominio non invia posta, usate «v=spf1 -all».',
            'notes' => [
                'ok' => 'SPF è impostato, :count query DNS sulle 10 ammesse.',
                'none' => 'Non c\'è un record SPF: chiunque può inviare email a vostro nome, e quelle vere finiscono più spesso nello spam.',
                'multiple' => 'Ci sono :count record SPF: i destinatari allora li ignorano tutti. Uniteli in uno.',
                'pass_all' => 'SPF finisce con +all: permette a qualsiasi server al mondo di inviare posta per il dominio.',
                'too_many_lookups' => 'SPF richiede :count query DNS, più delle 10 ammesse: i destinatari lo considerano rotto.',
                'neutral' => 'SPF finisce con ?all: non dice nulla sugli altri server, il che è quasi come non avere SPF.',
                'no_all' => 'SPF non finisce con ~all o -all: gli altri server non sono esclusi.',
            ],
        ],
        'M3' => [
            'title' => 'I destinatari sanno cosa fare delle email false?',
            'text' => 'Una policy DMARC con report',
            'fix' => 'Aggiungete un record TXT su _dmarc con la policy p=quarantine (o p=reject) e un indirizzo per i report in rua. Partite con p=none per un paio di settimane se non siete sicuri che tutti i vostri mittenti superino SPF e DKIM.',
            'notes' => [
                'ok' => 'DMARC è impostato con la policy :policy e i report.',
                'none' => 'Non c\'è DMARC: Gmail e Yahoo possono rifiutare o segnalare le email del dominio, e i falsi passano.',
                'multiple' => 'Ci sono :count record DMARC: i destinatari allora li ignorano tutti.',
                'invalid' => 'Il record DMARC non ha una policy p= valida.',
                'monitor' => 'La policy è p=none: i falsi vengono solo segnalati, non fermati.',
                'no_reports' => 'La policy è :policy, ma non c\'è un indirizzo rua: non saprete chi invia email a vostro nome.',
            ],
        ],
        'M4' => [
            'title' => 'Le email sono firmate?',
            'text' => 'Una chiave DKIM',
            'fix' => 'Attivate DKIM nel vostro servizio di posta e aggiungete al DNS il record che vi fornisce. Un\'email firmata dimostra di venire davvero dal vostro dominio.',
            'notes' => [
                'ok' => 'È stata trovata una chiave DKIM.',
                'not_found' => 'Nessuna chiave trovata con i nomi comuni. Il nome lo sceglie il vostro servizio di posta, quindi la chiave può esistere: controllate le sue impostazioni.',
                'no_mail' => 'Il dominio non riceve né invia posta.',
            ],
        ],
    ],
];
