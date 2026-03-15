@extends($activeTemplate . 'layouts.side_bar')

@php 
    $getResponse = collect(@$result['data']);
    $isAvailable = $getResponse->where('domain', @$result['domain'])->where('available', true)->count();
    $isUnavailable = $getResponse->where('domain', @$result['domain'])->where('available', false)->count();
@endphp

@section('data')

{{-- Inject scoped styles for this page --}}
@push('style')
<style>
    :root {
        --brand: #3FD1C0;
        --brand-glow: rgba(63, 209, 192, 0.35);
        --brand-soft: rgba(63, 209, 192, 0.12);
        --glass-bg: rgba(255, 255, 255, 0.07);
        --glass-border: rgba(255, 255, 255, 0.14);
        --glass-shadow: 0 8px 32px rgba(0,0,0,0.18);
        --radius: 16px;
        --radius-sm: 10px;
        --font: 'Manrope', 'Inter', sans-serif;
    }

    .dark-mode :root,
    [data-bs-theme="dark"] {
        --glass-bg: rgba(0, 0, 0, 0.25);
        --glass-border: rgba(255, 255, 255, 0.09);
    }

    /* Page wrapper */
    .dtech-domain-page {
        font-family: var(--font);
    }

    /* Page header */
    .dtech-page-header {
        margin-bottom: 2rem;
    }

    .dtech-page-header h3 {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--bs-heading-color, #0f172a);
        margin-bottom: 0.4rem;
    }

    .dark .dtech-page-header h3,
    [data-bs-theme="dark"] .dtech-page-header h3 {
        color: #f1f5f9;
    }

    .dtech-page-header p {
        font-size: 0.92rem;
        color: #64748b;
        max-width: 520px;
    }

    /* Search Section Glass Card */
    .dtech-search-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        box-shadow: var(--glass-shadow);
        padding: 2rem 2rem 1.75rem;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .dtech-search-card:hover {
        box-shadow: 0 8px 40px var(--brand-glow), var(--glass-shadow);
    }

    /* Status banners */
    .dtech-status-banner {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 1rem;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.4s ease both;
    }

    .dtech-status-available {
        background: rgba(63, 209, 192, 0.12);
        border-color: rgba(63, 209, 192, 0.4);
        color: #0f766e;
    }

    [data-bs-theme="dark"] .dtech-status-available {
        color: #5eead4;
    }

    .dtech-status-unavailable {
        background: rgba(239, 68, 68, 0.08);
        border-color: rgba(239, 68, 68, 0.3);
        color: #b91c1c;
    }

    [data-bs-theme="dark"] .dtech-status-unavailable {
        color: #fca5a5;
    }

    .dtech-status-unsupported {
        background: rgba(245, 158, 11, 0.08);
        border-color: rgba(245, 158, 11, 0.3);
        color: #92400e;
    }

    [data-bs-theme="dark"] .dtech-status-unsupported {
        color: #fcd34d;
    }

    .dtech-status-banner .status-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .dtech-status-available .status-icon { background: rgba(63, 209, 192, 0.2); }
    .dtech-status-unavailable .status-icon { background: rgba(239, 68, 68, 0.15); }
    .dtech-status-unsupported .status-icon { background: rgba(245, 158, 11, 0.15); }

    /* Domain Results List */
    .dtech-results-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .dtech-domain-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-sm);
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.25s ease;
        gap: 1rem;
        animation: fadeInUp 0.3s ease both;
    }

    .dtech-domain-row:hover {
        border-color: rgba(63, 209, 192, 0.35);
        box-shadow: 0 4px 24px var(--brand-glow);
        transform: translateY(-1px);
    }

    .dtech-domain-row.is-primary {
        border-color: rgba(63, 209, 192, 0.5);
        background: rgba(63, 209, 192, 0.06);
    }

    .dtech-domain-row.is-unavailable {
        opacity: 0.6;
    }

    /* Domain name */
    .domain-name-text {
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: -0.2px;
        color: var(--bs-body-color, #1e293b);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    [data-bs-theme="dark"] .domain-name-text {
        color: #e2e8f0;
    }

    .domain-tld-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.15rem 0.55rem;
        background: var(--brand-soft);
        border: 1px solid rgba(63, 209, 192, 0.25);
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--brand);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Domain actions */
    .domain-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .domain-price {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--brand);
        white-space: nowrap;
    }

    /* Unavailable label */
    .dtech-unavailable-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.85rem;
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #ef4444;
    }

    /* Glass Buttons */
    .btn-dtech-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.15rem;
        background: var(--brand);
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 0 0 0 var(--brand-glow);
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-dtech-primary:hover {
        background: #34bfaf;
        box-shadow: 0 4px 20px var(--brand-glow);
        transform: translateY(-1px);
        color: #0f172a;
    }

    .btn-dtech-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.15rem;
        background: transparent;
        color: var(--brand);
        font-weight: 700;
        font-size: 0.85rem;
        border: 1.5px solid rgba(63, 209, 192, 0.45);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-dtech-outline:hover {
        background: var(--brand-soft);
        border-color: var(--brand);
        box-shadow: 0 4px 16px var(--brand-glow);
        transform: translateY(-1px);
    }

    /* Empty state */
    .dtech-empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
    }

    .dtech-empty-state .empty-icon {
        width: 64px;
        height: 64px;
        background: var(--brand-soft);
        border: 1px solid rgba(63, 209, 192, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1.25rem;
    }

    .dtech-empty-state h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--bs-heading-color);
        margin-bottom: 0.4rem;
    }

    .dtech-empty-state p {
        font-size: 0.88rem;
        color: #94a3b8;
        max-width: 360px;
        margin: 0 auto;
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Stagger children */
    .dtech-results-list .dtech-domain-row:nth-child(1) { animation-delay: 0.05s; }
    .dtech-results-list .dtech-domain-row:nth-child(2) { animation-delay: 0.10s; }
    .dtech-results-list .dtech-domain-row:nth-child(3) { animation-delay: 0.15s; }
    .dtech-results-list .dtech-domain-row:nth-child(4) { animation-delay: 0.20s; }
    .dtech-results-list .dtech-domain-row:nth-child(5) { animation-delay: 0.25s; }
    .dtech-results-list .dtech-domain-row:nth-child(6) { animation-delay: 0.30s; }

    /* Responsive */
    @media (max-width: 576px) {
        .dtech-domain-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .domain-actions {
            justify-content: flex-start;
            width: 100%;
        }
        .dtech-search-card {
            padding: 1.25rem;
        }
    }
</style>
@endpush

<div class="col-lg-9 dtech-domain-page">
    <div class="row gy-4">

        {{-- ─── Page Header ─── --}}
        <div class="col-lg-12 dtech-page-header">
            <h3>@lang('Register Domain')</h3>
            <p>@lang('Find your perfect domain name. Enter your desired domain or keyword below to check availability') &mdash; @lang('instant results, no waiting').</p>
        </div>

        {{-- ─── Domain Search Form (glass card wrapper) ─── --}}
        <div class="col-lg-12">
            <div class="dtech-search-card">
                @include($activeTemplate . 'partials.domain_search_form')
            </div>
        </div>

        {{-- ─── Status Banners ─── --}}
        @if($isAvailable)
            <div class="col-lg-12">
                <div class="dtech-status-banner dtech-status-available">
                    <div class="status-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                    <div>
                        <div>
                            <strong>{{ @$result['domain'] }}</strong> @lang('is available')!
                        </div>
                        <small style="font-weight:500; opacity:0.8;">@lang('Great choice — add it to your cart now before it is taken.')</small>
                    </div>
                </div>
            </div>
        @elseif($isUnavailable)
            <div class="col-lg-12">
                <div class="dtech-status-banner dtech-status-unavailable">
                    <div class="status-icon">
                        <i class="las la-times-circle"></i>
                    </div>
                    <div>
                        <div>
                            <strong>{{ @$result['domain'] }}</strong> @lang('is unavailable').
                        </div>
                        <small style="font-weight:500; opacity:0.8;">@lang('Try a different extension or variation below.')</small>
                    </div>
                </div>
            </div>
        @endif

        @if(!@$result['isSupported'] && @$result['domain'])
            <div class="col-lg-12">
                <div class="dtech-status-banner dtech-status-unsupported">
                    <div class="status-icon">
                        <i class="las la-exclamation-triangle"></i>
                    </div>
                    <div>
                        @lang('We do not currently support the') <strong>({{ @$result['tld'] }})</strong> @lang('extension. Try one of the alternatives listed below.')
                    </div>
                </div>
            </div>
        @endif

        {{-- ─── Domain Results ─── --}}
        <div class="col-12">
            @if($getResponse->isNotEmpty())
                <div class="dtech-results-list">
                    @foreach($getResponse->sortByDesc('match') as $data)
                        @php
                            $isPrimary = (@$data['domain'] == @$result['domain']);
                        @endphp
                        <div class="dtech-domain-row {{ $isPrimary ? 'is-primary' : '' }} {{ !@$data['available'] ? 'is-unavailable' : '' }}">

                            {{-- Domain Name --}}
                            <div class="domain-name-text">
                                @php
                                    $parts = explode('.', @$data['domain'], 2);
                                    $sld = $parts[0] ?? '';
                                    $tld = isset($parts[1]) ? '.' . $parts[1] : '';
                                @endphp
                                <span>{{ $sld }}</span>
                                @if($tld)
                                    <span class="domain-tld-badge">{{ $tld }}</span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="domain-actions">
                                @if(@$data['available'])
                                    <span class="domain-price">
                                        {{ showAmount(@$data['setup']->pricing->firstPrice['price'] ?? 0) }}
                                        <small style="font-weight:500; font-size:0.72rem; opacity:0.75;">/yr</small>
                                    </span>

                                    <form action="{{ route('shopping.cart.add.domain') }}" method="post" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="domain" required value="{{ @$data['domain'] }}">
                                        <input type="hidden" name="domain_setup_id" required value="{{ @$data['setup']->id }}">
                                        <button type="submit" class="{{ $isPrimary ? 'btn-dtech-primary' : 'btn-dtech-outline' }}">
                                            <i class="las la-cart-plus"></i>
                                            @lang('Add to Cart')
                                        </button>
                                    </form>
                                @else
                                    <span class="dtech-unavailable-tag">
                                        <i class="las la-lock"></i>
                                        @lang('Unavailable')
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

            @elseif(!@$result['domain'])
                {{-- Initial empty state --}}
                <div class="dtech-empty-state">
                    <div class="empty-icon">
                        <i class="las la-globe" style="color: var(--brand);"></i>
                    </div>
                    <h5>@lang('Search for your domain')</h5>
                    <p>@lang('Type a domain name or keyword above to instantly check availability across dozens of extensions.')</p>
                </div>
            @endif
        </div>

    </div>
</div>

@endSection