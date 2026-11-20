@php($t = fn (string $key, array $replace = []) => __('dns-check::messages.'.$key, $replace, $locale))

<div class="wt" lang="{{ $locale }}" x-data="{
        step: 0,
        timer: null,
        start() { this.step = 0; clearInterval(this.timer); this.timer = setInterval(() => this.step = Math.min(this.step + 1, 2), 1200) },
        again() { this.$refs.form.scrollIntoView({ behavior: 'smooth', block: 'center' }); this.$refs.url.focus({ preventScroll: true }); this.$refs.url.select() },
    }" @wt-again="again()">
    <form class="wt-tool" wire:submit="analyze" @submit="start()" x-ref="form" novalidate>
        <div class="wt-row">
            <div @class(['wt-field', 'wt-field-err' => $errors->has('url')])>
                <input id="wt-url" type="text" inputmode="url" autocomplete="url" placeholder=" "
                       wire:model="url" x-ref="url">
                <label for="wt-url">{{ $t('form.label') }}</label>
            </div>
            <button type="submit" class="wt-go" wire:loading.attr="disabled" wire:target="analyze">
                <span wire:loading.remove wire:target="analyze">{{ $t('form.button') }}</span>
                <span wire:loading wire:target="analyze" class="wt-spin" aria-hidden="true"></span>
            </button>
        </div>
        <p class="wt-hint">{{ $t('form.hint', ['count' => config('dns-check.rate_limit.per_hour')]) }}</p>
        @error('url')<p class="wt-error">{{ $message }}</p>@enderror
        @if ($error)
            <p class="wt-error" role="alert">{{ $error }}</p>
        @endif
    </form>

    {{-- Lines light up on a timer while one request runs; the server does not report its steps --}}
    <div class="wt-log" wire:loading.block wire:target="analyze" aria-hidden="true">
        @foreach (['domain', 'mail', 'checks'] as $i => $line)
            <p :class="{ 'wt-run': step === {{ $i }}, 'wt-done': step > {{ $i }} }">{{ $t('loading.'.$line) }}</p>
        @endforeach
    </div>

    @if ($report)
        {{-- A new block for every check (keyed by the count), so each report scrolls the tool up to its form --}}
        <div wire:key="wt-report-{{ $reportNumber }}" wire:loading.remove wire:target="analyze" aria-live="polite"
             x-init="$root.scrollIntoView({ behavior: 'smooth', block: 'start' })">
            @php($domain = $report['domain'])
            <section class="dc-facts-box" aria-labelledby="dc-title">
                <h3 id="dc-title">{{ $domain['domain'] }}</h3>
                <dl class="dc-facts">
                    <div><dt>{{ $t('facts.expires') }}</dt>
                        <dd>@if($domain['expires']){{ \Illuminate\Support\Carbon::parse($domain['expires'])->locale($locale)->isoFormat('LL') }}@else — @endif
                            @if($domain['registrar'])<small>{{ $domain['registrar'] }}</small>@endif</dd></div>
                    <div><dt>{{ $t('facts.nameservers') }}</dt><dd>{{ implode(', ', array_slice($domain['nameservers'], 0, 3)) ?: '—' }}</dd></div>
                    <div><dt>{{ $t('facts.mail') }}</dt><dd>{{ implode(', ', array_slice(array_filter($domain['mx'], fn (string $host): bool => $host !== ''), 0, 3)) ?: '—' }}</dd></div>
                </dl>
            </section>

            @include('web-tools-core::checklist-report', ['report' => $report, 'tool' => 'dns-check', 'locale' => $locale])
        </div>
    @endif
</div>
