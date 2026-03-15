@extends($activeTemplate . 'layouts.side_bar')

@php
    $products = $serviceCategory->products($filter = true)->paginate(getPaginate());
@endphp

@push('style')
<style>
    /* ─── Page Header ─── */
    .dtech-cat-header {
        margin-bottom: 0.5rem;
    }

    .dtech-cat-header h3 {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.4px;
        color: hsl(var(--heading));
        margin-bottom: 0.4rem;
    }

    .dtech-cat-header p {
        font-size: 0.9rem;
        color: hsl(var(--body));
        max-width: 560px;
        line-height: 1.65;
    }

    /* ─── Product Card ─── */
    .dtech-product-card {
        position: relative;
        height: 100%;
        background: rgba(255, 255, 255, 0.58);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(63, 209, 192, 0.16);
        border-radius: 18px;
        box-shadow: 0 6px 28px rgba(63, 209, 192, 0.08), 0 2px 10px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .dtech-product-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3FD1C0, #2bb8a8);
        opacity: 0;
        transition: opacity 0.28s ease;
    }

    .dtech-product-card:hover {
        border-color: rgba(63, 209, 192, 0.35);
        box-shadow: 0 16px 48px rgba(63, 209, 192, 0.16), 0 4px 16px rgba(0,0,0,0.06);
        transform: translateY(-4px);
    }

    .dtech-product-card:hover::before {
        opacity: 1;
    }

    /* Card body */
    .dtech-product-card .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1rem;
        height: 100%;
    }

    /* ─── Product Name ─── */
    .dtech-product-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: hsl(var(--heading));
        letter-spacing: -0.2px;
        margin-bottom: 0.75rem;
    }

    /* ─── Stock Badge ─── */
    .dtech-stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.75rem;
        background: rgba(63, 209, 192, 0.10);
        border: 1px solid rgba(63, 209, 192, 0.28);
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #3FD1C0;
        margin-bottom: 0.85rem;
        letter-spacing: 0.2px;
    }

    .dtech-stock-badge i {
        font-size: 0.8rem;
    }

    /* ─── Pricing Block ─── */
    .dtech-pricing {
        padding: 1rem;
        background: rgba(63, 209, 192, 0.05);
        border: 1px solid rgba(63, 209, 192, 0.12);
        border-radius: 12px;
        margin-bottom: 0.85rem;
    }

    .dtech-price-main {
        font-size: 1.65rem;
        font-weight: 900;
        color: #3FD1C0;
        letter-spacing: -0.5px;
        line-height: 1.15;
        margin-bottom: 0.2rem;
    }

    .dtech-price-main .currency {
        font-size: 1rem;
        font-weight: 700;
        vertical-align: super;
        margin-right: 1px;
    }

    .dtech-price-main .period {
        font-size: 0.78rem;
        font-weight: 600;
        color: hsl(var(--body));
        opacity: 0.75;
    }

    .dtech-price-cycle {
        font-size: 0.8rem;
        font-weight: 600;
        color: hsl(var(--body));
        margin-bottom: 0.2rem;
        opacity: 0.8;
    }

    .dtech-price-setup {
        font-size: 0.75rem;
        font-weight: 500;
        color: hsl(var(--body));
        opacity: 0.65;
    }

    /* ─── Description ─── */
    .dtech-product-desc {
        font-size: 0.855rem;
        color: hsl(var(--body));
        line-height: 1.65;
        opacity: 0.85;
    }

    /* ─── Order Button ─── */
    .dtech-order-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        padding: 0.65rem 1rem;
        background: #3FD1C0;
        color: #0d2422;
        font-size: 0.875rem;
        font-weight: 800;
        border: 2px solid #3FD1C0;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.25s ease;
        letter-spacing: 0.1px;
        margin-top: auto;
    }

    .dtech-order-btn:hover {
        background: #2bb8a8;
        border-color: #2bb8a8;
        color: #0d2422;
        box-shadow: 0 6px 24px rgba(63, 209, 192, 0.38);
        transform: translateY(-1px);
    }

    .dtech-order-btn i {
        font-size: 1rem;
    }

    /* ─── Empty State ─── */
    .dtech-empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(63, 209, 192, 0.15);
        border-radius: 18px;
        box-shadow: 0 6px 28px rgba(63, 209, 192, 0.07);
    }

    .dtech-empty-icon {
        width: 68px;
        height: 68px;
        background: rgba(63, 209, 192, 0.08);
        border: 1px solid rgba(63, 209, 192, 0.20);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #3FD1C0;
        margin: 0 auto 1.25rem;
    }

    .dtech-empty-state h5 {
        font-size: 1.1rem;
        font-weight: 800;
        color: hsl(var(--heading));
        margin-bottom: 0.35rem;
    }

    .dtech-empty-state p {
        font-size: 0.875rem;
        color: hsl(var(--body));
        opacity: 0.7;
        max-width: 340px;
        margin: 0 auto;
    }

    /* ─── Card entrance animation ─── */
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dtech-product-col {
        animation: cardIn 0.35s ease both;
    }

    .dtech-product-col:nth-child(1) { animation-delay: 0.05s; }
    .dtech-product-col:nth-child(2) { animation-delay: 0.10s; }
    .dtech-product-col:nth-child(3) { animation-delay: 0.15s; }
    .dtech-product-col:nth-child(4) { animation-delay: 0.20s; }
    .dtech-product-col:nth-child(5) { animation-delay: 0.25s; }
    .dtech-product-col:nth-child(6) { animation-delay: 0.30s; }
</style>
@endpush

@section('data')
<div class="col-lg-9" bg-konik>
    <div class="row gy-4">

        {{-- ─── Category Header ─── --}}
        <div class="col-12 dtech-cat-header">
            <h3>{{ __($serviceCategory->name) }}</h3>
            <p class="mt-2">{{ $serviceCategory->short_description }}</p>
        </div>

        {{-- ─── Products Grid ─── --}}
        @forelse($products as $product)

            @php
                $price = $product->price;
                $setup = pricing($product->payment_type, $price, $type = 'setupFee');
            @endphp

            <div class="col-md-4 col-sm-6 dtech-product-col">
                <div class="dtech-product-card">
                    <div class="card-body">
                        <div>
                            {{-- Name --}}
                            <h5 class="dtech-product-name">{{ __($product->name) }}</h5>

                            {{-- Stock badge --}}
                            @if ($product->stock_control)
                                <div class="dtech-stock-badge">
                                    <i class="las la-box"></i>
                                    {{ $product->stock_quantity }} @lang('Available')
                                </div>
                            @endif

                            {{-- Pricing --}}
                            <div class="dtech-pricing">
                                <div class="dtech-price-main">
                                    <span class="currency">{{ gs('cur_sym') }}</span>{{ pricing($product->payment_type, $price, $type = 'price') }}
                                    <span class="period">/ {{ __(gs('cur_text')) }}</span>
                                </div>
                                <div class="dtech-price-cycle">
                                    {{ pricing($product->payment_type, $price, $type = 'price', $showText = true) }}
                                </div>
                                @if ($setup > 0)
                                    <div class="dtech-price-setup">
                                        {{ gs('cur_sym') }}{{ $setup }}
                                        {{ pricing($product->payment_type, $price, $type = 'setupFee', $showText = true) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            <div class="dtech-product-desc">
                                @php echo nl2br($product->description); @endphp
                            </div>
                        </div>

                        {{-- CTA --}}
                        <a href="{{ route('product.configure', ['categorySlug' => $serviceCategory->slug, 'productSlug' => $product->slug, 'id' => $product->id]) }}"
                           class="dtech-order-btn mt-3">
                            <i class="la la-shopping-bag"></i>
                            @lang('Order Now')
                        </a>

                    </div>
                </div>
            </div>

        @empty

            {{-- Empty state --}}
            <div class="col-12">
                <div class="dtech-empty-state">
                    <div class="dtech-empty-icon">
                        <i class="las la-box-open"></i>
                    </div>
                    <h5>@lang('No Products Available')</h5>
                    <p>@lang('No product available in this category') &mdash; @lang('check back soon or browse other categories.')</p>
                </div>
            </div>

        @endforelse

        {{-- ─── Pagination ─── --}}
        @if ($products->hasPages())
            <div class="col-12 mt-2">
                {{ paginateLinks($products) }}
            </div>
        @endif

    </div>
</div>
@endsection