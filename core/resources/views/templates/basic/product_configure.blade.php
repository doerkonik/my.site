@extends($activeTemplate . 'layouts.side_bar')

@push('style')
<style>
    /* ─── Domain Section ─── */
    .dtech-domain-section h3 {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: hsl(var(--heading));
        margin-bottom: 1.25rem;
    }

    /* Glass domain option card */
    .dtech-domain-card {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(63,209,192,0.18);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(63,209,192,0.08), 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 0.85rem;
        transition: all 0.25s ease;
    }

    .dtech-domain-card:hover {
        border-color: rgba(63,209,192,0.32);
        box-shadow: 0 10px 36px rgba(63,209,192,0.13);
    }

    .dtech-domain-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.85rem 1.1rem;
        background: rgba(63,209,192,0.07);
        border-bottom: 1px solid rgba(63,209,192,0.14);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 700;
        color: hsl(var(--heading));
        transition: background 0.2s;
    }

    .dtech-domain-card-header:hover { background: rgba(63,209,192,0.11); }

    /* Custom radio in header */
    .dtech-domain-radio {
        appearance: none;
        -webkit-appearance: none;
        width: 17px; height: 17px;
        border: 2px solid rgba(63,209,192,0.45);
        border-radius: 50%;
        position: relative;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .dtech-domain-radio::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%) scale(0);
        width: 7px; height: 7px;
        background: #3FD1C0;
        border-radius: 50%;
        transition: transform 0.2s ease;
    }
    .dtech-domain-radio:checked { border-color: #3FD1C0; }
    .dtech-domain-radio:checked::after { transform: translate(-50%,-50%) scale(1); }

    .dtech-domain-card-body { padding: 1.1rem 1.25rem; }

    /* Availability output */
    .dtech-availability-status {
        text-align: center;
        margin: 1.25rem 0 0.75rem;
        font-size: 1rem;
        font-weight: 700;
        min-height: 32px;
    }

    .showAvailability {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    /* Override domain-row from main.css for inline context */
    .showAvailability .domain-row {
        border-radius: 10px;
        border: 1px solid rgba(63,209,192,0.15);
        background: rgba(255,255,255,0.6);
    }

    .domain-row { order: 2; }
    .domain-row.domain-match { order: 1; }

    /* ─── Configure Section ─── */
    .dtech-configure-header {
        margin-bottom: 0.25rem;
    }
    .dtech-configure-header h3 {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: hsl(var(--heading));
    }
    .dtech-configure-header p {
        font-size: 0.875rem;
        color: hsl(var(--body));
        margin-top: 0.25rem;
    }

    /* Product detail card */
    .dtech-product-detail-card {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(63,209,192,0.18);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(63,209,192,0.07);
    }

    .dtech-product-detail-header {
        padding: 0.85rem 1.1rem;
        background: linear-gradient(135deg, rgba(63,209,192,0.14) 0%, rgba(63,209,192,0.05) 100%);
        border-bottom: 1px solid rgba(63,209,192,0.14);
    }

    .dtech-product-detail-header h5 {
        font-size: 1rem;
        font-weight: 800;
        color: #3FD1C0;
        margin: 0;
    }

    .dtech-product-detail-body {
        padding: 1.1rem 1.25rem;
        font-size: 0.855rem;
        color: hsl(var(--body));
        line-height: 1.65;
    }

    /* Form label style */
    .dtech-form-group { margin-bottom: 0; }
    .dtech-form-group label {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: hsl(var(--heading));
        margin-bottom: 6px;
        display: block;
    }

    /* Server configure section */
    .dtech-server-section {
        margin-top: 2rem;
    }
    .dtech-server-section-title {
        font-size: 1rem;
        font-weight: 800;
        text-align: center;
        color: hsl(var(--heading));
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(63,209,192,0.14);
    }

    /* ─── Order Summary Card ─── */
    .dtech-summary-card {
        background: rgba(255,255,255,0.58);
        backdrop-filter: blur(22px) saturate(160%);
        -webkit-backdrop-filter: blur(22px) saturate(160%);
        border: 1px solid rgba(63,209,192,0.18);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(63,209,192,0.10), 0 2px 10px rgba(0,0,0,0.04);
        position: sticky;
        top: 100px;
    }

    .dtech-summary-header {
        padding: 0.9rem 1.25rem;
        background: linear-gradient(135deg, rgba(63,209,192,0.13) 0%, rgba(63,209,192,0.04) 100%);
        border-bottom: 1px solid rgba(63,209,192,0.14);
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        color: #3FD1C0;
        text-align: center;
    }

    .dtech-summary-body { padding: 1.25rem; }

    .dtech-summary-product-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: hsl(var(--heading));
        margin-bottom: 0.15rem;
    }

    .dtech-summary-category {
        font-size: 0.78rem;
        color: hsl(var(--body));
        opacity: 0.7;
        font-style: italic;
        margin-bottom: 1rem;
    }

    .dtech-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.845rem;
        padding: 0.35rem 0;
        color: hsl(var(--body));
    }

    .dtech-summary-row span:last-child { font-weight: 700; color: hsl(var(--heading)); }

    .dtech-summary-divider {
        height: 1px;
        background: rgba(63,209,192,0.14);
        margin: 0.75rem 0;
    }

    .dtech-summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.5rem;
    }

    .dtech-summary-total-label {
        font-size: 1rem;
        font-weight: 800;
        color: hsl(var(--heading));
    }

    .dtech-summary-total-amount {
        font-size: 1.25rem;
        font-weight: 900;
        color: #3FD1C0;
        letter-spacing: -0.3px;
    }

    .dtech-configurable-price {
        font-size: 0.78rem;
        color: hsl(var(--body));
        opacity: 0.8;
        margin-top: 0.25rem;
    }

    /* Continue button */
    .dtech-continue-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
        background: #3FD1C0;
        color: #0d2422;
        font-size: 0.925rem;
        font-weight: 800;
        border: 2px solid #3FD1C0;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-top: 1.25rem;
        letter-spacing: 0.1px;
    }
    .dtech-continue-btn:hover {
        background: #2bb8a8;
        border-color: #2bb8a8;
        box-shadow: 0 6px 24px rgba(63,209,192,0.38);
        transform: translateY(-1px);
    }

    /* Out of stock */
    .dtech-out-of-stock {
        text-align: center;
        padding: 3rem 2rem;
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(239,68,68,0.18);
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(239,68,68,0.06);
    }
    .dtech-out-of-stock .oos-icon {
        width: 64px; height: 64px;
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.20);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; color: #ef4444;
        margin: 0 auto 1.1rem;
    }
    .dtech-out-of-stock h5 { font-weight: 800; color: hsl(var(--heading)); font-size: 1.05rem; }
</style>
@endpush

@section('data')
<div class="col-lg-9">
    <div class="row gy-4">

        {{-- ═══════════════════════════════
             OUT OF STOCK
        ═══════════════════════════════ --}}
        @if ($product->stock_control && $product->stock_quantity <= 0)
            <div class="col-12">
                <div class="dtech-out-of-stock">
                    <div class="oos-icon"><i class="las la-box-open"></i></div>
                    <h5>@lang('Sorry, Out of Stock')</h5>
                    <p class="mt-1" style="font-size:0.875rem; color:hsl(var(--body)); opacity:0.7;">
                        @lang('This product is currently unavailable. Please check back later.')
                    </p>
                </div>
            </div>

        @else

            {{-- ═══════════════════════════════
                 DOMAIN SELECTION
            ═══════════════════════════════ --}}
            @if ($product->domain_register && !@$cart)
                <div class="col-md-10 domainArea dtech-domain-section">
                    <h3>@lang('Choose a Domain')...</h3>

                    {{-- Register new domain --}}
                    <div class="dtech-domain-card">
                        <div class="dtech-domain-card-header">
                            <input type="radio" id="register_domain" class="dtech-domain-radio domain-option"
                                   data-form="register_domain_form" checked>
                            <label for="register_domain" role="button" style="cursor:pointer; margin:0;">
                                @lang('Register new domain')
                            </label>
                        </div>
                        <div class="dtech-domain-card-body">
                            <form action="" class="register_domain_form form">
                                <div class="row g-2">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"
                                                  style="background:rgba(63,209,192,0.07); border-color:rgba(63,209,192,0.22); font-weight:700; font-size:0.8rem; color:#3FD1C0;">
                                                @lang('WWW.')
                                            </span>
                                            <input type="text" name="domain_name" required
                                                   class="form-control form--control h-45 domain_name"
                                                   placeholder="@lang('example')">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <select class="form-control form--control h-45 form-select extension" name="extension" required>
                                            @foreach ($domains as $singleDomain)
                                                <option value="{{ $singleDomain->extension }}">{{ $singleDomain->extension }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <button type="submit" class="dtech-continue-btn exclude"
                                                style="padding:0.5rem 1rem; margin-top:0; font-size:0.85rem;">
                                            @lang('Check')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Use existing domain --}}
                    <div class="dtech-domain-card">
                        <div class="dtech-domain-card-header">
                            <input type="radio" id="existing_domain" class="dtech-domain-radio domain-option"
                                   data-form="domain_form">
                            <label for="existing_domain" role="button" style="cursor:pointer; margin:0;">
                                @lang('I will use my existing domain and update my nameservers')
                            </label>
                        </div>
                        <div class="dtech-domain-card-body d-none">
                            <form action="" class="domain_form form">
                                <div class="row g-2">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"
                                                  style="background:rgba(63,209,192,0.07); border-color:rgba(63,209,192,0.22); font-weight:700; font-size:0.8rem; color:#3FD1C0;">
                                                @lang('WWW.')
                                            </span>
                                            <input type="text" name="domain_name" required
                                                   class="form-control form--control h-45 domain_name"
                                                   placeholder="@lang('example')">
                                            <span class="input-group-text"
                                                  style="background:rgba(63,209,192,0.07); border-color:rgba(63,209,192,0.22); font-weight:700; color:#3FD1C0;">
                                                @lang('.')
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <input type="text" class="form-control form--control h-45 extension"
                                               placeholder="@lang('com')" required name="extension">
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <button type="submit" class="dtech-continue-btn exclude"
                                                style="padding:0.5rem 1rem; margin-top:0; font-size:0.85rem;">
                                            @lang('Use')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Availability results --}}
                    <div class="text-center mt-3 availability dtech-availability-status"></div>
                    <div class="showAvailability"></div>

                </div>
            @endif

            {{-- ═══════════════════════════════
                 PRODUCT CONFIGURE + SUMMARY
            ═══════════════════════════════ --}}
            <div class="col-md-12 {{ $product->domain_register ? 'd-none hideElement' : null }}">

                @if (!@$isUpdate)
                    <form action="{{ route('shopping.cart.add.service') }}" method="post">
                @else
                    <form action="{{ route('shopping.cart.config.service.update') }}" method="post">
                        <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                @endif
                @csrf

                {{-- Hidden fields --}}
                <input type="hidden" name="domain"     class="domain">
                <input type="hidden" name="domain_id"  value="0" class="domain_id" required>
                <input type="hidden" name="product_id" value="{{ $product->id }}" required>

                <div class="row g-3">

                    {{-- ── LEFT: Configure ── --}}
                    <div class="col-lg-8">

                        <div class="row gy-3">

                            {{-- Configure header --}}
                            <div class="col-12 dtech-configure-header">
                                <h3>@lang('Product Configure')</h3>
                                <p>@lang('Configure your desired options and continue to checkout')</p>
                            </div>

                            @php $price = $product->price; @endphp

                            {{-- Product detail --}}
                            <div class="col-12">
                                <div class="dtech-product-detail-card">
                                    <div class="dtech-product-detail-header">
                                        <h5>{{ __($product->name) }}</h5>
                                    </div>
                                    <div class="dtech-product-detail-body">
                                        @php echo nl2br($product->description); @endphp
                                    </div>
                                </div>
                            </div>

                            {{-- Billing cycle --}}
                            <div class="col-12 {{ $product->payment_type == 1 ? 'd-none' : '' }}">
                                <div class="dtech-form-group">
                                    <label>@lang('Choose Billing Type')</label>
                                    <select name="billing_cycle" class="form-control form--control h-45 form-select">
                                        @php echo pricing($product->payment_type, $price); @endphp
                                    </select>
                                </div>
                            </div>

                            {{-- Configurable options --}}
                            @php $configs = $product->getConfigs; @endphp

                            @foreach ($configs as $config)
                                @php
                                    $group   = $config->activeGroup;
                                    $options = $group->activeOptions;
                                @endphp

                                @foreach ($options->sortBy('order') as $option)
                                    @php $subOptions = $option->activeSubOptions; @endphp

                                    @if (count($subOptions))
                                        <div class="col-md-6">
                                            <div class="dtech-form-group">
                                                <label>{{ __($option->name) }}</label>
                                                <select name="config_options[{{ $option->id }}]"
                                                        class="form-control form--control h-45 options form-select"
                                                        data-type=''
                                                        data-name="{{ __($option->name) }}">
                                                    @foreach ($subOptions->sortBy('order') as $subOption)
                                                        <option value="{{ $subOption->id }}"
                                                                data-price='{{ $subOption->getOnlyPrice }}'
                                                                data-text='{{ __($subOption->name) }}'>
                                                            {{ __($subOption->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach

                            {{-- Server config (product_type == 3) --}}
                            @if ($product->product_type == 3)
                                <div class="col-12 dtech-server-section">
                                    <div class="dtech-server-section-title">
                                        <i class="las la-server" style="color:#3FD1C0; margin-right:6px;"></i>
                                        @lang('Configure Server')
                                    </div>
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <div class="dtech-form-group">
                                                <label>@lang('Hostname')</label>
                                                <input type="text" name="hostname"
                                                       class="form-control form--control h-45 hostname"
                                                       placeholder="servername.example.com" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dtech-form-group">
                                                <label>@lang('Root Password')</label>
                                                <input type="password" name="password"
                                                       class="form-control form--control h-45 root_password"
                                                       placeholder="*******" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dtech-form-group">
                                                <label>@lang('NS1 Prefix')</label>
                                                <input type="text" name="ns1"
                                                       class="form-control form--control h-45 ns1_prefix"
                                                       placeholder="ns1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dtech-form-group">
                                                <label>@lang('NS2 Prefix')</label>
                                                <input type="text" name="ns2"
                                                       class="form-control form--control h-45 ns2_prefix"
                                                       placeholder="ns2" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- ── RIGHT: Order Summary ── --}}
                    <div class="col-lg-4">
                        <div class="dtech-summary-card">

                            <div class="dtech-summary-header">
                                <i class="las la-receipt" style="margin-right:5px;"></i>
                                @lang('Order Summary')
                            </div>

                            <div class="dtech-summary-body">

                                {{-- Product name + category --}}
                                <div class="dtech-summary-product-name">{{ __($product->name) }}</div>
                                <div class="dtech-summary-category">{{ $product->serviceCategory->name }}</div>

                                {{-- Line items --}}
                                <div class="dtech-summary-row">
                                    <span>{{ __($product->name) }}</span>
                                    <span>
                                        {{ gs('cur_sym') }}<span class="basicPrice">{{ pricing($product->payment_type, $price, $type = 'price') }}</span>
                                        {{ __(gs('cur_text')) }}
                                    </span>
                                </div>

                                {{-- Configurable add-ons --}}
                                <div class="configurablePrice dtech-configurable-price"></div>

                                <div class="dtech-summary-divider"></div>

                                {{-- Setup fee --}}
                                <div class="dtech-summary-row calculatePrice">
                                    <span>@lang('Setup Fee')</span>
                                    <span>
                                        {{ gs('cur_sym') }}<span class="setupFee">{{ pricing($product->payment_type, $price, $type = 'setupFee') }}</span>
                                        {{ __(gs('cur_text')) }}
                                    </span>
                                </div>

                                {{-- Billing cycle price --}}
                                <div class="dtech-summary-row">
                                    <span class="billingType">{{ pricing($product->payment_type, $price, $type = 'price', $showText = true) }}</span>
                                    <span>
                                        {{ gs('cur_sym') }}<span class="billingPrice">{{ pricing($product->payment_type, $price, $type = 'price') }}</span>
                                        {{ __(gs('cur_text')) }}
                                    </span>
                                </div>

                                <div class="dtech-summary-divider"></div>

                                {{-- Total --}}
                                <div class="dtech-summary-total">
                                    <span class="dtech-summary-total-label">@lang('Total')</span>
                                    <span class="dtech-summary-total-amount">
                                        {{ gs('cur_sym') }}<span class="finalAmount">{{ pricing($product->payment_type, $price, $type = 'price') + pricing($product->payment_type, $price, $type = 'setupFee') }}</span>
                                        <small style="font-size:0.75rem; font-weight:600; opacity:0.7;">{{ __(gs('cur_text')) }}</small>
                                    </span>
                                </div>

                                {{-- Continue button --}}
                                <button type="submit" class="dtech-continue-btn">
                                    @lang('Continue')
                                    <i class="la la-arrow-circle-right"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
                </form>
            </div>

        @endif
    </div>
</div>
@endsection

@push('script')
<script>
(function($) {
    "use strict";

    var general      = @json(gs(['cur_sym', 'cur_text']));
    var product      = @json($product);
    var productPrice = @json($product->price);
    var allOptions   = $('.options');

    var globalSetup    = "{{ pricing($product->payment_type, @$price, $type = 'setupFee') }}";
    var addingSetupFee = 0;
    var globalPrice    = "{{ pricing($product->payment_type, @$price, $type = 'price') }}";
    var addingPrice    = 0;

    var basicPrice   = $('.basicPrice');
    var billingType  = $('.billingType');
    var setupFee     = $('.setupFee');
    var billingPrice = $('.billingPrice');
    var finalAmount  = $('.finalAmount');
    var info         = '';

    /* ── Domain option toggle ── */
    $('.domain-option').on('click', function() {
        var form = $(this).data('form');
        if (form == 'register_domain_form') {
            $('input[data-form=domain_form]').prop('checked', false);
            $('.register_domain_form').closest('.dtech-domain-card-body').removeClass('d-none');
            return $('.domain_form').closest('.dtech-domain-card-body').addClass('d-none');
        }
        $('input[data-form=register_domain_form]').prop('checked', false);
        $('.register_domain_form').closest('.dtech-domain-card-body').addClass('d-none');
        return $('.domain_form').closest('.dtech-domain-card-body').removeClass('d-none');
    });

    /* ── Domain register logic ── */
    if (product.domain_register) {

        var domains      = @json($domains);
        var hideElement  = $('.hideElement');
        var domainArea   = $('.domainArea');

        $('.register_domain_form').on('submit', function(e) {
            e.preventDefault();
            var sld = $(this).find('.domain_name').val();
            var tld = $(this).find('.extension :selected').val();
            if (!sld) return false;
            $('.showAvailability').empty();
            $('.availability').empty();
            checkDomain(sld + tld);
        });

        $('.domain_form').on('submit', function(e) {
            e.preventDefault();
            var domainName = $(this).find('.domain_name').val();
            var extension  = $(this).find('.extension').val();
            var domain     = domainName + '.' + extension;
            if (domain) {
                $('.domain').val(domain);
                $('.domain_id').val(0);
                hideElement.removeClass('d-none');
                domainArea.addClass('d-none');
            }
        });

        function checkDomain(domain) {
            $.ajax({
                url: "{{ route('search.domain') }}",
                data: { domain: domain },
                beforeSend: function() {
                    $('.availability').html(`<span style="color:hsl(var(--body)); opacity:0.7; font-weight:600;">@lang('Loading')...</span>`);
                },
                success: function(getResponse) {
                    if (!getResponse['success']) {
                        $('.availability').html('');
                        var errors = getResponse['message'];
                        if (typeof(errors) != 'object') errors = [errors];
                        $.each(errors, function(index, message) {
                            return $('.availability').append(`<span style="color:#ef4444; font-weight:700;">${message}</span>`);
                        });
                        return false;
                    }

                    var response  = getResponse.result;
                    var available = false;

                    $.each(response.data.sort(function(a, b) { return b.match - a.match; }), function(key, data) {
                        var domain = data.domain;
                        var setup  = data.setup;
                        var match  = data.match;
                        var button = `<span style="color:#3FD1C0; font-weight:700; font-size:0.8rem; padding:3px 10px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:50px;">@lang('Unavailable')</span>`;

                        if (response.domain == domain && data.available) {
                            available = true;
                        }

                        if (data.available) {
                            button = `
                                <span style="font-weight:800; color:#3FD1C0;">${general.cur_sym}${parseFloat(setup.pricing.firstPrice['price'] ?? 0).toFixed(2)}</span>
                                <button
                                    class="registerDomainBtn ms-2"
                                    data-domain="${domain}"
                                    data-id="${setup.id}"
                                    style="padding:4px 14px; background:${match ? '#3FD1C0' : 'transparent'}; color:${match ? '#0d2422' : '#3FD1C0'}; border:1.5px solid rgba(63,209,192,0.5); border-radius:50px; font-weight:700; font-size:0.8rem; cursor:pointer; transition:all 0.2s;"
                                    onmouseover="this.style.background='#3FD1C0'; this.style.color='#0d2422'; this.style.boxShadow='0 4px 14px rgba(63,209,192,0.35)';"
                                    onmouseout="this.style.background='${match ? '#3FD1C0' : 'transparent'}'; this.style.color='${match ? '#0d2422' : '#3FD1C0'}'; this.style.boxShadow='none';">
                                    <i class="la la-cart-plus"></i> @lang('Add')
                                </button>`;
                        }

                        var html = `<div class="domain-row ${match ? 'domain-match' : ''}">
                            <span style="font-weight:600; font-size:0.875rem;">${domain}</span>
                            <div class="text-end">${button}</div>
                        </div>`;

                        $('.showAvailability').append(html);
                    });

                    if (available) {
                        $('.availability').html(`<span style="font-size:1rem; font-weight:800; color:hsl(var(--heading));">@lang('Congratulations')! <span style="color:#3FD1C0;">${response.domain}</span> @lang('is available')!</span>`);
                    } else {
                        $('.availability').html(`<span style="font-size:1rem; font-weight:800;"><span style="color:#ef4444;">${response.domain}</span> @lang('is unavailable')</span>`);
                    }
                },
                error: function(error) {
                    $('.availability').html(`<span style="color:#ef4444; font-weight:700;">${error.responseJSON.messages}</span>`);
                }
            });
        }

        $(document).on('click', '.registerDomainBtn', function() {
            $('.domain').val($(this).data('domain'));
            $('.domain_id').val($(this).data('id'));
            hideElement.removeClass('d-none');
            domainArea.addClass('d-none');
        });
    }

    /* ── Billing cycle change ── */
    $('select[name=billing_cycle]').on('change', function() {
        var value = $(this).val();
        var price = pricing(productPrice, 'price', value);
        var setup = pricing(productPrice, 'setupFee', value);
        var type  = pricing(0, null, value);
        var total = pricing(productPrice, null, value);

        billingType.text(type);
        basicPrice.text(price);
        billingPrice.text(price);
        setupFee.text(setup);
        finalAmount.text(total);
        allOptions.attr('data-type', value);

        globalSetup = setup;
        globalPrice = price;

        showSelect(value);
    }).change();

    /* ── Config option change ── */
    allOptions.on('change', function() {
        var column   = $(this).attr('data-type');
        showSelect(column, false);
    });

    /* ── Pricing helper ── */
    function pricing(price, type, column) {
        try {
            if (!price) {
                column = column.replaceAll('_', ' ');
                if (product.payment_type == 1) column = 'One Time:';
                return column.replaceAll(/(?:^|\s)\S/g, function(word) { return word.toUpperCase(); });
            }
            if (!type) {
                var p   = productPrice[column];
                var fee = productPrice[column + '_setup_fee'];
                return getAmount(parseFloat(fee) + parseFloat(p));
            }
            var amount = 0;
            if (type == 'price') {
                amount = productPrice[column];
            } else {
                amount = productPrice[column + '_setup_fee'];
            }
            return getAmount(amount);
        } catch (message) {
            console.log(message);
        }
    }

    function getAmount(val, length = 2) {
        return parseFloat(val).toFixed(length);
    }

    function sum(p1, p2) {
        return getAmount(parseFloat(p1) + parseFloat(p2));
    }

    function showSelect(value, showDropdown = true) {
        try {
            addingSetupFee = 0;
            addingPrice    = 0;

            var getColumn    = value;
            var getFeeColumn = value + '_setup_fee';

            allOptions.each(function(index, data) {
                var options   = $(data).find('option');
                var finalText = null;

                options.each(function(iteration, dropdown) {
                    var dropdown      = $(dropdown);
                    var optionSetupFee = '';

                    if (dropdown.data('price')) {
                        var priceForThisItem = dropdown.data('price');
                        var mainText         = dropdown.data('text');
                        var display          = product.payment_type == 1 ? 'One Time' : pricing(0, null, getColumn);

                        if (product.payment_type == 1) getColumn = 'monthly';

                        if (priceForThisItem[getFeeColumn] > 0) {
                            optionSetupFee = ` + ${general.cur_sym}${getAmount(priceForThisItem[getFeeColumn])} ${general.cur_text} Setup Fee`;
                        }

                        var dropdownOptions = `${general.cur_sym}${getAmount(priceForThisItem[getColumn])} ${general.cur_text} ${display} ${optionSetupFee}`;
                        finalText = mainText + ' ' + dropdownOptions;

                        if (showDropdown) dropdown.text(finalText);
                    }

                    if (dropdown.filter(':selected').attr('data-price')) {
                        var configurableOption = $('.configurablePrice');
                        configurableOption.empty();

                        var priceForThisItem = dropdown.data('price');

                        info += `<div class='d-flex justify-content-between mt-1' style="font-size:0.78rem; color:hsl(var(--body));">
                            <span><i class='las la-angle-double-right' style="color:#3FD1C0;"></i> ${$(data).data('name')}:</span>
                            <span style="font-weight:700;">${finalText}</span>
                        </div>`;

                        configurableOption.append(info);

                        addingSetupFee = sum(addingSetupFee, priceForThisItem[getFeeColumn]);
                        addingPrice    = sum(addingPrice,    priceForThisItem[getColumn]);

                        setupFee.text(sum(addingSetupFee, globalSetup));
                        billingPrice.text(sum(addingPrice, globalPrice));
                        finalAmount.text(sum(sum(addingSetupFee, globalSetup), sum(addingPrice, globalPrice)));
                    }
                });
            });

            info = '';
        } catch (message) {
            console.log(message);
        }
    }

    /* ── Update/cart restore ── */
    @if (@$cart)
        var cart        = @json(@$cart);
        var billingCycle = '{{ $billingCycle }}';
        var column      = billingCycle;

        $(`select[name=billing_cycle] option[value=${column}]`).prop('selected', true).change();
        $('select[name=billing_cycle]').parent().hide();

        $('.hideElement').removeClass('d-none');
        $('.domainArea').addClass('d-none');

        $('.domain').val(cart.domain);
        $('.hostname').val(cart.hostname);
        $('.root_password').val(cart.password);
        $('.ns1_prefix').val(cart.ns1);
        $('.ns2_prefix').val(cart.ns2);

        var length = Object.keys(cart.config_options).length;
        for (var i = 0; i <= length; i++) {
            var selectName   = Object.keys(cart.config_options)[i];
            var selectOption = Object.values(cart.config_options)[i];

            $(`select[name='config_options[${selectName}]'] option[value=${selectOption}]`).prop('selected', true);

            var price = pricing(productPrice, 'price',    column);
            var setup = pricing(productPrice, 'setupFee', column);
            var type  = pricing(0, null, column);
            var total = pricing(productPrice, null, column);

            billingType.text(type);
            basicPrice.text(price);
            billingPrice.text(price);
            setupFee.text(setup);
            finalAmount.text(total);
            allOptions.attr('data-type', column);

            globalSetup = setup;
            globalPrice = price;

            showSelect(column, false);
        }
    @endif

})(jQuery);
</script>
@endpush