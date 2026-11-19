<?php

return [
    'form' => [
        'label' => '域名或网站地址',
        'button' => '检查',
        'hint' => '免费 · 无需注册 · 每小时最多 :count 次',
    ],
    'loading' => [
        'domain' => '正在读取域名注册信息',
        'mail' => '正在读取 DNS 和邮件记录',
        'checks' => '正在检查 SPF、DKIM 和 DMARC',
    ],
    'errors' => [
        'invalid_url' => '请输入域名，例如 example.com',
        'no_domain' => 'DNS 中没有这个域名，请检查拼写',
    ],
    'verdicts' => [
        'ready' => '域名和邮件设置良好',
        'almost' => '域名和邮件设置基本良好',
        'partly' => '域名和邮件设置需要完善',
        'not_ready' => '域名和邮件设置较差',
    ],
    'facts' => [
        'expires' => '注册有效期至',
        'nameservers' => '域名服务器',
        'mail' => '邮件服务器',
    ],
    'groups' => [
        'D' => ['title' => '域名', 'text' => '注册信息、网站记录和域名服务器'],
        'M' => ['title' => '邮件', 'text' => '决定域名的邮件进入收件箱还是垃圾邮件的设置'],
    ],
    'checks' => [
        'D1' => [
            'title' => '域名快到期了吗？',
            'text' => '注册期至少还剩 45 天',
            'fix' => '在注册商处续费域名，并用不会过期的银行卡开启自动续费。域名过期会立即让网站和邮件同时停止工作，而且可能被别人注册。',
            'notes' => [
                'ok' => '注册有效期至 :date，还剩 :days 天。',
                'soon' => '只剩 :days 天，到 :date 为止。请确认它会自动续费。',
                'urgent' => '只剩 :days 天，到 :date 为止。请立即续费。',
                'expired' => '注册已于 :date 到期。',
                'unknown' => '注册局未显示到期日期。',
                'no_rdap' => '该域名区的注册局未通过 RDAP 公开注册信息。',
            ],
        ],
        'D2' => [
            'title' => '带 www 和不带 www 都能打开网站吗？',
            'text' => '域名和 www 的记录',
            'fix' => '让域名和 www 都指向服务器：域名使用 A 记录，www 使用 CNAME。然后设置从一个地址重定向到另一个，让网站只有一个主地址。',
            'notes' => [
                'ok' => '域名和 www 都指向服务器。',
                'no_www' => 'www.:domain 没有记录：输入 www 的访客会看到错误。',
                'www_only' => '不带 www 的 :domain 没有记录：输入短地址的访客会看到错误。',
                'none' => ':domain 和 www 都没有指向服务器：网站打不开。',
            ],
        ],
        'D3' => [
            'title' => '某台 DNS 服务器宕机时域名还能解析吗？',
            'text' => '至少 2 台位于不同网络的域名服务器',
            'fix' => '使用至少两台位于不同网络的域名服务器。注册商和 Cloudflare 的 DNS 默认就是如此；域名服务器可在注册商的控制面板中更改。',
            'notes' => [
                'ok' => '域名服务器 :count 台，位于不同网络。',
                'single' => '域名服务器只有 :count 台。一旦宕机，网站和邮件都会停止工作。',
                'same_network' => '域名服务器有 :count 台，但都在同一网络：一次故障就会让它们全部停止。',
            ],
        ],
        'D4' => [
            'title' => '是否限制了谁能为域名签发 SSL？',
            'text' => 'CAA 记录',
            'fix' => '添加一条 CAA 记录，写明为您签发证书的机构，例如 letsencrypt.org。这样其他机构就会拒绝为您的域名签发证书。',
            'notes' => [
                'ok' => 'CAA 指定了允许的证书颁发机构。',
                'none' => '没有 CAA 记录：任何机构都可以为该域名签发证书。',
            ],
        ],
        'M1' => [
            'title' => '域名能接收邮件吗？',
            'text' => '指向真实服务器的 MX 记录',
            'fix' => '添加邮件服务（Google Workspace、Microsoft 365、Zoho 或您的主机商）提供的 MX 记录。如果域名从不接收邮件，请添加“空 MX”记录“0 .”，让发件方立即知道。',
            'notes' => [
                'ok' => '邮件服务器 :count 台，全部存在。',
                'null' => '该域名声明不接收邮件（空 MX）。',
                'none' => '没有 MX 记录：发往该域名地址的邮件无法送达。',
                'broken' => '以下邮件服务器在 DNS 中不存在：:hosts。',
            ],
        ],
        'M2' => [
            'title' => '是否规定了哪些服务器可以代表域名发信？',
            'text' => '只有一条以 ~all 或 -all 结尾的 SPF 记录',
            'fix' => '只保留一条以 v=spf1 开头的 TXT 记录，包含所有代表您发信的服务（邮箱、网站、邮件群发），并以 ~all 结尾。如果域名不发送邮件，请使用“v=spf1 -all”。',
            'notes' => [
                'ok' => 'SPF 已设置，DNS 查询 :count 次，上限为 10 次。',
                'none' => '没有 SPF 记录：任何人都能以您的名义发信，真实邮件也更容易进入垃圾箱。',
                'multiple' => '有 :count 条 SPF 记录：收件方会全部忽略。请合并为一条。',
                'pass_all' => 'SPF 以 +all 结尾：允许世界上任何服务器代表该域名发信。',
                'too_many_lookups' => 'SPF 需要 :count 次 DNS 查询，超过了 10 次的上限：收件方会视其为无效。',
                'neutral' => 'SPF 以 ?all 结尾：对其他服务器不作任何说明，几乎等于没有 SPF。',
                'no_all' => 'SPF 没有以 ~all 或 -all 结尾：没有排除其他服务器。',
            ],
        ],
        'M3' => [
            'title' => '收件方知道如何处理伪造邮件吗？',
            'text' => '带报告的 DMARC 策略',
            'fix' => '在 _dmarc 添加一条 TXT 记录，策略为 p=quarantine（或 p=reject），并在 rua 中填写接收报告的地址。如果不确定所有发信方都能通过 SPF 和 DKIM，可以先用 p=none 观察两周。',
            'notes' => [
                'ok' => 'DMARC 已设置，策略为 :policy，并开启了报告。',
                'none' => '没有 DMARC：Gmail 和 Yahoo 可能拒收或标记该域名的邮件，而伪造邮件却能通过。',
                'multiple' => '有 :count 条 DMARC 记录：收件方会全部忽略。',
                'invalid' => 'DMARC 记录中没有有效的 p= 策略。',
                'monitor' => '策略为 p=none：伪造邮件只会被报告，不会被拦截。',
                'no_reports' => '策略为 :policy，但没有 rua 地址：您无法得知谁在以您的名义发信。',
            ],
        ],
        'M4' => [
            'title' => '邮件有签名吗？',
            'text' => 'DKIM 密钥',
            'fix' => '在邮件服务中开启 DKIM，并把它提供的记录添加到 DNS。带签名的邮件能证明它确实来自您的域名。',
            'notes' => [
                'ok' => '找到了 DKIM 密钥。',
                'not_found' => '在常见名称下没有找到密钥。名称由您的邮件服务设定，因此密钥仍可能存在：请查看该服务的设置。',
                'no_mail' => '该域名既不接收也不发送邮件。',
            ],
        ],
    ],
];
