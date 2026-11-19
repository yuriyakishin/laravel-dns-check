<?php

return [
    'form' => [
        'label' => 'Domaine ou adresse du site',
        'button' => 'Vérifier',
        'hint' => 'Gratuit · sans inscription · jusqu\'à :count vérifications par heure',
    ],
    'loading' => [
        'domain' => 'Lecture de l\'enregistrement du domaine',
        'mail' => 'Lecture des enregistrements DNS et mail',
        'checks' => 'Vérification de SPF, DKIM et DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Saisissez un domaine, par exemple example.com',
        'no_domain' => 'Ce domaine n\'existe pas dans le DNS. Vérifiez l\'orthographe',
    ],
    'verdicts' => [
        'ready' => 'Le domaine et le mail sont bien configurés',
        'almost' => 'Le domaine et le mail sont presque bien configurés',
        'partly' => 'Le domaine et le mail sont à améliorer',
        'not_ready' => 'Le domaine et le mail sont mal configurés',
    ],
    'facts' => [
        'expires' => 'Enregistré jusqu\'au',
        'nameservers' => 'Serveurs DNS',
        'mail' => 'Serveurs de messagerie',
    ],
    'groups' => [
        'D' => ['title' => 'Le domaine', 'text' => 'L\'enregistrement, les entrées du site et les serveurs DNS'],
        'M' => ['title' => 'Le mail', 'text' => 'Ce qui décide si les courriels du domaine arrivent en boîte de réception ou en spam'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Le domaine expire-t-il bientôt ?',
            'text' => 'Au moins 45 jours d\'enregistrement restants',
            'fix' => 'Renouvelez le domaine chez votre registraire et activez le renouvellement automatique avec une carte qui n\'expirera pas. Un domaine expiré coupe aussitôt le site et le mail, et quelqu\'un d\'autre peut l\'enregistrer.',
            'notes' => [
                'ok' => 'Enregistré jusqu\'au :date, encore :days jours.',
                'soon' => 'Plus que :days jours, jusqu\'au :date. Vérifiez qu\'il se renouvelle automatiquement.',
                'urgent' => 'Plus que :days jours, jusqu\'au :date. Renouvelez-le maintenant.',
                'expired' => 'L\'enregistrement a pris fin le :date.',
                'unknown' => 'Le registre n\'indique pas de date d\'expiration.',
                'no_rdap' => 'Le registre de cette zone ne publie pas les données d\'enregistrement via RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'Le site s\'ouvre-t-il avec et sans www ?',
            'text' => 'Des entrées pour le domaine et pour www',
            'fix' => 'Faites pointer le domaine et www vers le serveur : une entrée A pour le domaine et un CNAME pour www. Puis mettez en place une redirection de l\'un vers l\'autre pour que le site ait une seule adresse principale.',
            'notes' => [
                'ok' => 'Le domaine et www pointent vers un serveur.',
                'no_www' => 'www.:domain n\'a pas d\'entrée : ceux qui tapent www obtiennent une erreur.',
                'www_only' => ':domain sans www n\'a pas d\'entrée : ceux qui tapent l\'adresse courte obtiennent une erreur.',
                'none' => 'Ni :domain ni www ne pointent vers un serveur : le site ne s\'ouvre pas.',
            ],
        ],
        'D3' => [
            'title' => 'Le domaine répond-il si un serveur DNS tombe ?',
            'text' => 'Au moins 2 serveurs DNS dans des réseaux différents',
            'fix' => 'Utilisez au moins deux serveurs DNS dans des réseaux différents. Les DNS des registraires et de Cloudflare le font par défaut ; les serveurs DNS se changent dans le panneau de votre registraire.',
            'notes' => [
                'ok' => 'Serveurs DNS : :count, dans des réseaux différents.',
                'single' => 'Serveurs DNS : :count. S\'il tombe, le site et le mail s\'arrêtent.',
                'same_network' => 'Serveurs DNS : :count, mais tous dans le même réseau : une panne les arrête tous.',
            ],
        ],
        'D4' => [
            'title' => 'Est-il limité qui peut émettre un SSL pour le domaine ?',
            'text' => 'Une entrée CAA',
            'fix' => 'Ajoutez une entrée CAA avec l\'autorité qui émet vos certificats, par exemple letsencrypt.org. Les autres autorités refuseront alors d\'émettre un certificat pour votre domaine.',
            'notes' => [
                'ok' => 'CAA nomme les autorités autorisées.',
                'none' => 'Il n\'y a pas d\'entrée CAA : n\'importe quelle autorité peut émettre un certificat pour le domaine.',
            ],
        ],
        'M1' => [
            'title' => 'Le domaine reçoit-il du courrier ?',
            'text' => 'Des entrées MX qui mènent à de vrais serveurs',
            'fix' => 'Ajoutez les entrées MX que donne votre service de messagerie (Google Workspace, Microsoft 365, Zoho, votre hébergeur). Si le domaine ne reçoit jamais de courrier, ajoutez une entrée « MX nul » « 0 . » pour que les expéditeurs le sachent aussitôt.',
            'notes' => [
                'ok' => 'Serveurs de messagerie : :count, tous existent.',
                'null' => 'Le domaine indique qu\'il ne reçoit pas de courrier (MX nul).',
                'none' => 'Il n\'y a pas d\'entrées MX : les courriels envoyés aux adresses du domaine n\'arriveront pas.',
                'broken' => 'Ces serveurs de messagerie n\'existent pas dans le DNS : :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Est-il défini quels serveurs envoient le courrier du domaine ?',
            'text' => 'Une seule entrée SPF, qui finit par ~all ou -all',
            'fix' => 'Gardez une seule entrée TXT qui commence par v=spf1, inclut chaque service qui envoie du courrier pour vous (messagerie, site, newsletters) et finit par ~all. Si le domaine n\'envoie aucun courrier, utilisez « v=spf1 -all ».',
            'notes' => [
                'ok' => 'SPF est défini, :count requêtes DNS sur 10 autorisées.',
                'none' => 'Il n\'y a pas d\'entrée SPF : n\'importe qui peut envoyer des courriels en votre nom, et les vrais finissent plus souvent en spam.',
                'multiple' => 'Il y a :count entrées SPF : les destinataires les ignorent alors toutes. Fusionnez-les en une seule.',
                'pass_all' => 'SPF finit par +all : il autorise n\'importe quel serveur au monde à envoyer du courrier pour le domaine.',
                'too_many_lookups' => 'SPF demande :count requêtes DNS, plus que les 10 autorisées : les destinataires le considèrent comme cassé.',
                'neutral' => 'SPF finit par ?all : il ne dit rien des autres serveurs, ce qui revient presque à ne pas avoir de SPF.',
                'no_all' => 'SPF ne finit pas par ~all ou -all : les autres serveurs ne sont pas exclus.',
            ],
        ],
        'M3' => [
            'title' => 'Les destinataires savent-ils quoi faire des faux courriels ?',
            'text' => 'Une politique DMARC avec rapports',
            'fix' => 'Ajoutez une entrée TXT sur _dmarc avec la politique p=quarantine (ou p=reject) et une adresse pour les rapports dans rua. Commencez par p=none pendant deux semaines si vous n\'êtes pas sûr que tous vos expéditeurs passent SPF et DKIM.',
            'notes' => [
                'ok' => 'DMARC est défini avec la politique :policy et des rapports.',
                'none' => 'Il n\'y a pas de DMARC : Gmail et Yahoo peuvent rejeter ou signaler les courriels du domaine, et les faux passent.',
                'multiple' => 'Il y a :count entrées DMARC : les destinataires les ignorent alors toutes.',
                'invalid' => 'L\'entrée DMARC n\'a pas de politique p= valide.',
                'monitor' => 'La politique est p=none : les faux sont seulement signalés, pas arrêtés.',
                'no_reports' => 'La politique est :policy, mais il n\'y a pas d\'adresse rua : vous ne saurez pas qui envoie des courriels en votre nom.',
            ],
        ],
        'M4' => [
            'title' => 'Les courriels sont-ils signés ?',
            'text' => 'Une clé DKIM',
            'fix' => 'Activez DKIM dans votre service de messagerie et ajoutez au DNS l\'entrée qu\'il vous donne. Un courriel signé prouve qu\'il vient vraiment de votre domaine.',
            'notes' => [
                'ok' => 'Une clé DKIM a été trouvée.',
                'not_found' => 'Aucune clé trouvée sous les noms courants. Le nom est fixé par votre service de messagerie, la clé peut donc exister : vérifiez ses réglages.',
                'no_mail' => 'Le domaine ne reçoit ni n\'envoie de courrier.',
            ],
        ],
    ],
];
