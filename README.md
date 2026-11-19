# DNS Check

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/php-%5E8.4-777bb4.svg)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-13-ff2d20.svg)](composer.json)
[![Livewire](https://img.shields.io/badge/livewire-4-fb70a9.svg)](composer.json)
[![Live Demo](https://img.shields.io/badge/live%20demo-yu.net.ua-brightgreen.svg)](https://yu.net.ua/en/tools/dns-check)

A Livewire tool for Laravel that checks a domain's DNS and mail records. The visitor enters a domain or URL. The tool reads the records and the registration data (over RDAP), then explains in plain words what works and what to fix.

**🔗 Live example: [yu.net.ua/en/tools/dns-check](https://yu.net.ua/en/tools/dns-check)**

## What it checks

8 checks in two groups. The result is a score out of 100 and a verdict.

| Group | Checks |
|---|---|
| **The domain** | Expiry date, site opens with and without `www`, more than one name server, CAA limits who can issue SSL |
| **The email** | MX records, SPF, DMARC, DKIM signature |

DKIM keys have no fixed name, so the tool tries the selectors used by popular mail services (Google, Microsoft 365, SendGrid, Zoho, Resend and others). You can change that list in the config.

## Requirements

- PHP ^8.4
- Laravel ^13.0
- Livewire ^4.0

## Installation

```bash
composer require yu-laravel/dns-check
```

This also installs [`yu-laravel/web-tools-core`](https://github.com/yuriyakishin/web-tools-core). Both service providers are discovered automatically.

## Usage

Put the component on a page whose layout includes `@livewireStyles` and `@livewireScripts`:

```blade
<livewire:dns-check />
```

Import the styles into your CSS build:

```css
@import "../../vendor/yu-laravel/web-tools-core/resources/css/web-tools-core.css";
@import "../../vendor/yu-laravel/dns-check/resources/css/dns-check.css";
```

To match your site's colours and fonts, override the `--wt-*` variables (see [Web Tools Core](https://github.com/yuriyakishin/web-tools-core#styles)).

## Configuration

| Env variable | Default | Description |
|---|---|---|
| `DNS_CHECK_ENABLED` | `true` | Turn the tool off without uninstalling it. |
| `DNS_CHECK_PER_HOUR` | `10` | Checks per visitor IP per hour. |

A report is cached for 5 minutes, because DNS changes take a few minutes to spread. `config/dns-check.php` also holds the DKIM selectors to try and the IANA RDAP bootstrap URL.

## Events

After every check the tool dispatches `Yu\WebToolsCore\Events\ToolCheckedEvent` with `tool = "dns-check"` (see [Listening to checks](https://github.com/yuriyakishin/web-tools-core#listening-to-checks)).

## Languages

13 translations: cs, de, en, es, fr, it, ja, nl, pl, pt, tr, uk and zh. The language is taken from the visitor's browser, with your app locale as the fallback.

## Limitations

Records are read with PHP's `dns_get_record()` through your server's resolver. A resolver that drops large or TCP answers can return some records empty.

## License

MIT. See [LICENSE](LICENSE).
