<?php

declare(strict_types=1);

return [
    'form' => [
        'label' => 'Domínio ou endereço do site',
        'button' => 'Verificar',
        'hint' => 'Grátis · sem registo · até :count verificações por hora',
    ],
    'loading' => [
        'domain' => 'A ler o registo do domínio',
        'mail' => 'A ler os registos DNS e de email',
        'checks' => 'A verificar SPF, DKIM e DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Escreva um domínio, por exemplo example.com',
        'no_domain' => 'Este domínio não existe no DNS. Confirme como está escrito',
    ],
    'verdicts' => [
        'ready' => 'O domínio e o email estão bem configurados',
        'almost' => 'O domínio e o email estão quase bem configurados',
        'partly' => 'O domínio e o email precisam de ajustes',
        'not_ready' => 'O domínio e o email estão mal configurados',
    ],
    'facts' => [
        'expires' => 'Registado até',
        'nameservers' => 'Servidores DNS',
        'mail' => 'Servidores de email',
    ],
    'groups' => [
        'D' => ['title' => 'O domínio', 'text' => 'O registo, os registos do site e os servidores DNS'],
        'M' => ['title' => 'O email', 'text' => 'O que decide se os emails do domínio chegam à caixa de entrada ou ao spam'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'O domínio expira em breve?',
            'text' => 'Faltam pelo menos 45 dias de registo',
            'fix' => 'Renove o domínio no registrador e ative a renovação automática com um cartão que não expire. Um domínio expirado deita abaixo o site e o email de imediato, e outra pessoa pode registá-lo.',
            'notes' => [
                'ok' => 'Registado até :date, faltam :days dias.',
                'soon' => 'Faltam só :days dias, até :date. Confirme que se renova automaticamente.',
                'urgent' => 'Faltam só :days dias, até :date. Renove-o agora.',
                'expired' => 'O registo terminou a :date.',
                'unknown' => 'O registo não mostra a data de expiração.',
                'no_rdap' => 'O registo desta zona não publica os dados de registo por RDAP.',
            ],
        ],
        'D2' => [
            'title' => 'O site abre com e sem www?',
            'text' => 'Registos para o domínio e para www',
            'fix' => 'Aponte o domínio e o www para o servidor: um registo A para o domínio e um CNAME para o www. Depois configure um redirecionamento de um para o outro, para o site ter um endereço principal.',
            'notes' => [
                'ok' => 'O domínio e o www apontam para um servidor.',
                'no_www' => 'www.:domain não tem registo: quem escreve www vê um erro.',
                'www_only' => ':domain sem www não tem registo: quem escreve o endereço curto vê um erro.',
                'none' => 'Nem :domain nem www apontam para um servidor: o site não abre.',
            ],
        ],
        'D3' => [
            'title' => 'O domínio responde se um servidor DNS falhar?',
            'text' => 'Pelo menos 2 servidores DNS em redes diferentes',
            'fix' => 'Use pelo menos dois servidores DNS em redes diferentes. Os DNS dos registradores e da Cloudflare já o fazem; os servidores DNS mudam-se no painel do registrador.',
            'notes' => [
                'ok' => 'Servidores DNS: :count, em redes diferentes.',
                'single' => 'Servidores DNS: :count. Se falhar, o site e o email deixam de funcionar.',
                'same_network' => 'Servidores DNS: :count, mas todos na mesma rede: uma falha deita-os todos abaixo.',
            ],
        ],
        'D4' => [
            'title' => 'Está limitado quem pode emitir SSL para o domínio?',
            'text' => 'Um registo CAA',
            'fix' => 'Adicione um registo CAA com a autoridade que emite os seus certificados, por exemplo letsencrypt.org. Assim as outras autoridades recusam emitir um certificado para o seu domínio.',
            'notes' => [
                'ok' => 'O CAA indica as autoridades permitidas.',
                'none' => 'Não há registo CAA: qualquer autoridade pode emitir um certificado para o domínio.',
            ],
        ],
        'M1' => [
            'title' => 'O domínio recebe email?',
            'text' => 'Registos MX que levam a servidores reais',
            'fix' => 'Adicione os registos MX que o seu serviço de email indica (Google Workspace, Microsoft 365, Zoho, o seu alojamento). Se o domínio nunca recebe email, adicione um registo «MX nulo» «0 .» para os remetentes saberem de imediato.',
            'notes' => [
                'ok' => 'Servidores de email: :count, todos existem.',
                'null' => 'O domínio indica que não recebe email (MX nulo).',
                'none' => 'Não há registos MX: os emails para endereços do domínio não chegam.',
                'broken' => 'Estes servidores de email não existem no DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Está definido que servidores enviam o email do domínio?',
            'text' => 'Um só registo SPF, que termina em ~all ou -all',
            'fix' => 'Mantenha um só registo TXT que comece por v=spf1, inclua cada serviço que envia email por si (email, site, newsletters) e termine em ~all. Se o domínio não envia email, use «v=spf1 -all».',
            'notes' => [
                'ok' => 'O SPF está definido, :count consultas DNS das 10 permitidas.',
                'none' => 'Não há registo SPF: qualquer pessoa pode enviar emails em seu nome, e os verdadeiros vão mais vezes para o spam.',
                'multiple' => 'Há :count registos SPF: os destinatários ignoram-nos todos. Junte-os num só.',
                'pass_all' => 'O SPF termina em +all: permite a qualquer servidor do mundo enviar email pelo domínio.',
                'too_many_lookups' => 'O SPF precisa de :count consultas DNS, mais do que as 10 permitidas: os destinatários consideram-no partido.',
                'neutral' => 'O SPF termina em ?all: não diz nada sobre outros servidores, o que é quase o mesmo que não ter SPF.',
                'no_all' => 'O SPF não termina em ~all ou -all: outros servidores não ficam excluídos.',
            ],
        ],
        'M3' => [
            'title' => 'Os destinatários sabem o que fazer com emails falsos?',
            'text' => 'Uma política DMARC com relatórios',
            'fix' => 'Adicione um registo TXT em _dmarc com a política p=quarantine (ou p=reject) e um endereço para relatórios em rua. Comece com p=none durante duas semanas se não tiver a certeza de que todos os seus remetentes passam no SPF e no DKIM.',
            'notes' => [
                'ok' => 'O DMARC está definido com a política :policy e relatórios.',
                'none' => 'Não há DMARC: o Gmail e o Yahoo podem rejeitar ou marcar os emails do domínio, e os falsos passam.',
                'multiple' => 'Há :count registos DMARC: os destinatários ignoram-nos todos.',
                'invalid' => 'O registo DMARC não tem uma política p= válida.',
                'monitor' => 'A política é p=none: os falsos só são reportados, não travados.',
                'no_reports' => 'A política é :policy, mas não há endereço rua: não vai saber quem envia emails em seu nome.',
            ],
        ],
        'M4' => [
            'title' => 'Os emails estão assinados?',
            'text' => 'Uma chave DKIM',
            'fix' => 'Ative o DKIM no seu serviço de email e adicione ao DNS o registo que ele indicar. Um email assinado prova que vem mesmo do seu domínio.',
            'notes' => [
                'ok' => 'Foi encontrada uma chave DKIM.',
                'not_found' => 'Não foi encontrada nenhuma chave com os nomes habituais. O nome é definido pelo seu serviço de email, por isso a chave pode existir: confirme as definições dele.',
                'no_mail' => 'O domínio não recebe nem envia email.',
            ],
        ],
    ],
];
