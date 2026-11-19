<?php

declare(strict_types=1);

return [
    'form' => [
        'label' => 'Alan adı veya site adresi',
        'button' => 'Kontrol et',
        'hint' => 'Ücretsiz · kayıt yok · saatte en fazla :count kontrol',
    ],
    'loading' => [
        'domain' => 'Alan adı kaydı okunuyor',
        'mail' => 'DNS ve e-posta kayıtları okunuyor',
        'checks' => 'SPF, DKIM ve DMARC kontrol ediliyor',
    ],
    'errors' => [
        'invalid_url' => 'Bir alan adı girin, örneğin example.com',
        'no_domain' => 'Bu alan adı DNS\'te yok. Yazımı kontrol edin',
    ],
    'verdicts' => [
        'ready' => 'Alan adı ve e-posta iyi ayarlanmış',
        'almost' => 'Alan adı ve e-posta neredeyse iyi ayarlanmış',
        'partly' => 'Alan adı ve e-postanın düzeltilmesi gerekiyor',
        'not_ready' => 'Alan adı ve e-posta kötü ayarlanmış',
    ],
    'facts' => [
        'expires' => 'Kayıt bitişi',
        'nameservers' => 'Ad sunucuları',
        'mail' => 'E-posta sunucuları',
    ],
    'groups' => [
        'D' => ['title' => 'Alan adı', 'text' => 'Kayıt, site kayıtları ve ad sunucuları'],
        'M' => ['title' => 'E-posta', 'text' => 'Alan adının e-postalarının gelen kutusuna mı spama mı düşeceğini belirleyenler'],
    ],
    'checks' => [
        'D1' => [
            'title' => 'Alan adının süresi yakında doluyor mu?',
            'text' => 'En az 45 günlük kayıt kaldı',
            'fix' => 'Alan adını kayıt şirketinizde yenileyin ve süresi dolmayacak bir kartla otomatik yenilemeyi açın. Süresi dolan bir alan adı siteyi ve e-postayı anında kapatır ve başkası onu kaydedebilir.',
            'notes' => [
                'ok' => ':date tarihine kadar kayıtlı, :days gün kaldı.',
                'soon' => 'Yalnızca :days gün kaldı, :date tarihine kadar. Otomatik yenilendiğinden emin olun.',
                'urgent' => 'Yalnızca :days gün kaldı, :date tarihine kadar. Hemen yenileyin.',
                'expired' => 'Kayıt :date tarihinde sona erdi.',
                'unknown' => 'Kayıt kuruluşu bitiş tarihini göstermiyor.',
                'no_rdap' => 'Bu bölgenin kayıt kuruluşu kayıt bilgilerini RDAP üzerinden yayınlamıyor.',
            ],
        ],
        'D2' => [
            'title' => 'Site www ile ve www olmadan açılıyor mu?',
            'text' => 'Alan adı ve www için kayıtlar',
            'fix' => 'Hem alan adını hem www\'yi sunucuya yönlendirin: alan adı için bir A kaydı, www için bir CNAME. Sonra sitenin tek bir ana adresi olması için birinden diğerine yönlendirme kurun.',
            'notes' => [
                'ok' => 'Hem alan adı hem www bir sunucuya gidiyor.',
                'no_www' => 'www.:domain için kayıt yok: www yazanlar hata görür.',
                'www_only' => 'www olmadan :domain için kayıt yok: kısa adresi yazanlar hata görür.',
                'none' => 'Ne :domain ne de www bir sunucuya gidiyor: site açılmıyor.',
            ],
        ],
        'D3' => [
            'title' => 'Bir DNS sunucusu çökerse alan adı yanıt verir mi?',
            'text' => 'Farklı ağlarda en az 2 ad sunucusu',
            'fix' => 'Farklı ağlarda en az iki ad sunucusu kullanın. Kayıt şirketlerinin ve Cloudflare\'in DNS hizmetleri bunu varsayılan olarak sağlar; ad sunucuları kayıt şirketinizin panelinden değiştirilir.',
            'notes' => [
                'ok' => 'Ad sunucuları: :count, farklı ağlarda.',
                'single' => 'Ad sunucuları: :count. Çökerse site ve e-posta çalışmaz.',
                'same_network' => 'Ad sunucuları: :count, ama hepsi aynı ağda: tek bir arıza hepsini durdurur.',
            ],
        ],
        'D4' => [
            'title' => 'Alan adı için kimin SSL verebileceği sınırlı mı?',
            'text' => 'Bir CAA kaydı',
            'fix' => 'Sertifikalarınızı veren kuruluşu belirten bir CAA kaydı ekleyin, örneğin letsencrypt.org. O zaman diğer kuruluşlar alan adınız için sertifika vermeyi reddeder.',
            'notes' => [
                'ok' => 'CAA izin verilen kuruluşları belirtiyor.',
                'none' => 'CAA kaydı yok: herhangi bir kuruluş alan adı için sertifika verebilir.',
            ],
        ],
        'M1' => [
            'title' => 'Alan adı e-posta alıyor mu?',
            'text' => 'Gerçek sunuculara giden MX kayıtları',
            'fix' => 'E-posta hizmetinizin verdiği MX kayıtlarını ekleyin (Google Workspace, Microsoft 365, Zoho, hosting\'iniz). Alan adı hiç e-posta almıyorsa, gönderenlerin bunu hemen bilmesi için bir «null MX» kaydı «0 .» ekleyin.',
            'notes' => [
                'ok' => 'E-posta sunucuları: :count, hepsi mevcut.',
                'null' => 'Alan adı e-posta almadığını bildiriyor (null MX).',
                'none' => 'MX kaydı yok: alan adındaki adreslere gönderilen e-postalar ulaşmaz.',
                'broken' => 'Bu e-posta sunucuları DNS\'te yok: :hosts.',
            ],
        ],
        'M2' => [
            'title' => 'Alan adının e-postasını hangi sunucuların gönderdiği belli mi?',
            'text' => '~all veya -all ile biten tek bir SPF kaydı',
            'fix' => 'v=spf1 ile başlayan, sizin adınıza e-posta gönderen her hizmeti (e-posta, site, bültenler) içeren ve ~all ile biten tek bir TXT kaydı tutun. Alan adı e-posta göndermiyorsa «v=spf1 -all» kullanın.',
            'notes' => [
                'ok' => 'SPF ayarlı, izin verilen 10 DNS sorgusundan :count kullanılıyor.',
                'none' => 'SPF kaydı yok: herkes sizin adınıza e-posta gönderebilir ve gerçek e-postalar daha sık spama düşer.',
                'multiple' => ':count SPF kaydı var: alıcılar o zaman hepsini yok sayar. Bunları tek kayıtta birleştirin.',
                'pass_all' => 'SPF +all ile bitiyor: dünyadaki her sunucunun alan adı adına e-posta göndermesine izin veriyor.',
                'too_many_lookups' => 'SPF :count DNS sorgusu gerektiriyor, izin verilen 10\'dan fazla: alıcılar onu bozuk sayar.',
                'neutral' => 'SPF ?all ile bitiyor: diğer sunucular hakkında hiçbir şey söylemiyor, bu da neredeyse SPF olmamasıyla aynı.',
                'no_all' => 'SPF ~all veya -all ile bitmiyor: diğer sunucular dışlanmamış.',
            ],
        ],
        'M3' => [
            'title' => 'Alıcılar sahte e-postalarla ne yapacaklarını biliyor mu?',
            'text' => 'Raporlu bir DMARC politikası',
            'fix' => '_dmarc üzerinde p=quarantine (veya p=reject) politikası ve rua içinde rapor adresi olan bir TXT kaydı ekleyin. Tüm gönderenlerinizin SPF ve DKIM\'den geçtiğinden emin değilseniz birkaç hafta p=none ile başlayın.',
            'notes' => [
                'ok' => 'DMARC :policy politikası ve raporlarla ayarlı.',
                'none' => 'DMARC yok: Gmail ve Yahoo alan adının e-postalarını reddedebilir veya işaretleyebilir, sahteler ise geçer.',
                'multiple' => ':count DMARC kaydı var: alıcılar o zaman hepsini yok sayar.',
                'invalid' => 'DMARC kaydında geçerli bir p= politikası yok.',
                'monitor' => 'Politika p=none: sahteler yalnızca raporlanıyor, durdurulmuyor.',
                'no_reports' => 'Politika :policy, ama rua adresi yok: sizin adınıza kimin e-posta gönderdiğini öğrenemezsiniz.',
            ],
        ],
        'M4' => [
            'title' => 'E-postalar imzalı mı?',
            'text' => 'Bir DKIM anahtarı',
            'fix' => 'E-posta hizmetinizde DKIM\'i açın ve verdiği kaydı DNS\'e ekleyin. İmzalı bir e-posta gerçekten alan adınızdan geldiğini kanıtlar.',
            'notes' => [
                'ok' => 'Bir DKIM anahtarı bulundu.',
                'not_found' => 'Yaygın adlar altında anahtar bulunamadı. Adı e-posta hizmetiniz belirler, yani anahtar yine de olabilir: hizmetin ayarlarını kontrol edin.',
                'no_mail' => 'Alan adı e-posta almıyor ve göndermiyor.',
            ],
        ],
    ],
];
