<?php

namespace Yu\DnsCheck\Livewire;

use Illuminate\Contracts\View\View;
use Yu\DnsCheck\Services\DnsAnalyzer;
use Yu\WebToolsCore\Livewire\ToolComponent;
use Yu\WebToolsCore\Locale\LocaleResolver;

/**
 * The domain, DNS and mail check: the form, the loading log, the domain facts and the checklist report.
 */
class DnsCheckComponent extends ToolComponent
{
    protected function name(): string
    {
        return 'dns-check';
    }

    protected function locales(): LocaleResolver
    {
        return new LocaleResolver(dirname(__DIR__, 2).'/resources/lang');
    }

    protected function run(string $url, string $locale): array
    {
        return app(DnsAnalyzer::class)->analyze($url, $locale);
    }

    public function render(): View
    {
        return view('dns-check::dns-check');
    }
}
