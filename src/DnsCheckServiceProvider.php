<?php

namespace Yu\DnsCheck;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Yu\DnsCheck\Livewire\DnsCheckComponent;

class DnsCheckServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dns-check.php', 'dns-check');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dns-check');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dns-check');

        // The name is required: without it Livewire 4 makes up a hash and <livewire:dns-check /> is not found
        Livewire::component('dns-check', DnsCheckComponent::class);
    }
}
