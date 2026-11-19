<?php

return [
    'form' => [
        'label' => 'Dominio o dirección del sitio',
        'button' => 'Comprobar',
        'hint' => 'Gratis · sin registro · hasta :count comprobaciones por hora',
    ],
    'loading' => [
        'domain' => 'Leyendo el registro del dominio',
        'mail' => 'Leyendo los registros DNS y de correo',
        'checks' => 'Comprobando SPF, DKIM y DMARC',
    ],
    'errors' => [
        'invalid_url' => 'Escriba un dominio, por ejemplo example.com',
        'no_domain' => 'Este dominio no existe en el DNS. Revise cómo está escrito',
    ],
    'verdicts' => [
        'ready' => 'El dominio y el correo están bien configurados',
        'almost' => 'El dominio y el correo están casi bien configurados',
        'partly' => 'El dominio y el correo necesitan ajustes',
        'not_ready' => 'El dominio y el correo están mal configurados',
    ],
    'facts' => [
        'expires' => 'Registrado hasta',
        'nameservers' => 'Servidores DNS',
        'mail' => 'Servidores de correo',
    ],
    'groups' => [
        'D' => ['title' => 'El dominio', 'text' => 'El registro, los registros del sitio y los servidores DNS'],
        'M' => ['title' => 'El correo', 'text' => 'De qué depende que los correos del dominio lleguen a la bandeja de entrada y no al spam'],
    ],
    'checks' => [
        'D1' => [
            'title' => '¿Caduca pronto el dominio?',
            'text' => 'Quedan al menos 45 días de registro',
            'fix' => 'Renueve el dominio en su registrador y active la renovación automática con una tarjeta que no caduque. Un dominio caducado tumba al instante el sitio y el correo, y otra persona puede registrarlo.',
            'notes' => [
                'ok' => 'Registrado hasta el :date, quedan :days días.',
                'soon' => 'Solo quedan :days días, hasta el :date. Asegúrese de que se renueva automáticamente.',
                'urgent' => 'Solo quedan :days días, hasta el :date. Renuévelo ahora.',
                'expired' => 'El registro terminó el :date.',
                'unknown' => 'El registro no muestra la fecha de caducidad.',
                'no_rdap' => 'El registro de esta zona no publica los datos de registro por RDAP.',
            ],
        ],
        'D2' => [
            'title' => '¿Se abre el sitio con y sin www?',
            'text' => 'Registros para el dominio y para www',
            'fix' => 'Haga que el dominio y www apunten al servidor: un registro A para el dominio y un CNAME para www. Después configure una redirección de uno al otro para que el sitio tenga una dirección principal.',
            'notes' => [
                'ok' => 'El dominio y www apuntan a un servidor.',
                'no_www' => 'www.:domain no tiene registro: quien escribe www ve un error.',
                'www_only' => ':domain sin www no tiene registro: quien escribe la dirección corta ve un error.',
                'none' => 'Ni :domain ni www apuntan a un servidor: el sitio no se abre.',
            ],
        ],
        'D3' => [
            'title' => '¿Responde el dominio si cae un servidor DNS?',
            'text' => 'Al menos 2 servidores DNS en redes distintas',
            'fix' => 'Use al menos dos servidores DNS en redes distintas. Los DNS de los registradores y de Cloudflare ya lo hacen; los servidores DNS se cambian en el panel de su registrador.',
            'notes' => [
                'ok' => 'Servidores DNS: :count, en redes distintas.',
                'single' => 'Servidores DNS: :count. Si cae, dejan de funcionar el sitio y el correo.',
                'same_network' => 'Servidores DNS: :count, pero todos en la misma red: un fallo los tumba a todos.',
            ],
        ],
        'D4' => [
            'title' => '¿Está limitado quién puede emitir SSL para el dominio?',
            'text' => 'Un registro CAA',
            'fix' => 'Añada un registro CAA con la autoridad que emite sus certificados, por ejemplo letsencrypt.org. Así las demás autoridades se negarán a emitir un certificado para su dominio.',
            'notes' => [
                'ok' => 'CAA indica las autoridades permitidas.',
                'none' => 'No hay registro CAA: cualquier autoridad puede emitir un certificado para el dominio.',
            ],
        ],
        'M1' => [
            'title' => '¿Recibe correo el dominio?',
            'text' => 'Registros MX que apuntan a servidores reales',
            'fix' => 'Añada los registros MX que le da su servicio de correo (Google Workspace, Microsoft 365, Zoho, su hosting). Si el dominio nunca recibe correo, añada un registro «MX nulo» «0 .» para que los remitentes lo sepan al momento.',
            'notes' => [
                'ok' => 'Servidores de correo: :count, todos existen.',
                'null' => 'El dominio indica que no recibe correo (MX nulo).',
                'none' => 'No hay registros MX: los correos a las direcciones del dominio no llegarán.',
                'broken' => 'Estos servidores de correo no existen en el DNS: :hosts.',
            ],
        ],
        'M2' => [
            'title' => '¿Está definido qué servidores envían el correo del dominio?',
            'text' => 'Un solo registro SPF, terminado en ~all o -all',
            'fix' => 'Mantenga un solo registro TXT que empiece por v=spf1, incluya cada servicio que envía correo por usted (correo, sitio, boletines) y termine en ~all. Si el dominio no envía correo, use «v=spf1 -all».',
            'notes' => [
                'ok' => 'SPF está definido, :count consultas DNS de las 10 permitidas.',
                'none' => 'No hay registro SPF: cualquiera puede enviar correos en su nombre, y los reales acaban más a menudo en spam.',
                'multiple' => 'Hay :count registros SPF: entonces los destinatarios los ignoran todos. Únalos en uno.',
                'pass_all' => 'SPF termina en +all: permite a cualquier servidor del mundo enviar correo por el dominio.',
                'too_many_lookups' => 'SPF necesita :count consultas DNS, más de las 10 permitidas: los destinatarios lo consideran roto.',
                'neutral' => 'SPF termina en ?all: no dice nada sobre otros servidores, que es casi lo mismo que no tener SPF.',
                'no_all' => 'SPF no termina en ~all o -all: otros servidores no quedan excluidos.',
            ],
        ],
        'M3' => [
            'title' => '¿Saben los destinatarios qué hacer con los correos falsos?',
            'text' => 'Una política DMARC con informes',
            'fix' => 'Añada un registro TXT en _dmarc con la política p=quarantine (o p=reject) y una dirección para informes en rua. Empiece con p=none un par de semanas si no está seguro de que todos sus remitentes pasan SPF y DKIM.',
            'notes' => [
                'ok' => 'DMARC está definido con la política :policy e informes.',
                'none' => 'No hay DMARC: Gmail y Yahoo pueden rechazar o marcar los correos del dominio, y los falsos pasan.',
                'multiple' => 'Hay :count registros DMARC: entonces los destinatarios los ignoran todos.',
                'invalid' => 'El registro DMARC no tiene una política p= válida.',
                'monitor' => 'La política es p=none: los falsos solo se informan, no se detienen.',
                'no_reports' => 'La política es :policy, pero no hay dirección rua: no sabrá quién envía correos en su nombre.',
            ],
        ],
        'M4' => [
            'title' => '¿Están firmados los correos?',
            'text' => 'Una clave DKIM',
            'fix' => 'Active DKIM en su servicio de correo y añada al DNS el registro que le dé. Un correo firmado demuestra que de verdad viene de su dominio.',
            'notes' => [
                'ok' => 'Se encontró una clave DKIM.',
                'not_found' => 'No se encontró ninguna clave con los nombres habituales. El nombre lo fija su servicio de correo, así que la clave puede existir: revise su configuración.',
                'no_mail' => 'El dominio no recibe ni envía correo.',
            ],
        ],
    ],
];
