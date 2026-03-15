@extends($activeTemplate . 'layouts.side_bar')

@section('data')
<div class="col-lg-9">
    <div class="row g-3">
        <div class="col-lg-8">
            <h4 class="mb-3" style="font-family:'Manrope',sans-serif; font-weight:800;">@lang('Cart')</h4>

            @forelse($carts as $cart)
                <div class="card fs-12 cart_child mb-2">
                    <div class="card-body">
                        <div class="row align-items-center g-2">
                            <div class="col-md-11 d-flex justify-content-between flex-wrap align-items-center gap-2">
                                @if ($cart->product_id && !$cart->domain_setup_id && !$cart->domain_id)
                                    <div>
                                        <h6 class="d-inline me-2" style="font-weight:800;">{{ __($cart->product->name) }}</h6>
                                        <a href="{{ route('shopping.cart.config.service', $cart->id) }}" class="text--primary" style="font-size:0.8rem; font-weight:700;">
                                            <i class="la la-pencil"></i> @lang('Edit')
                                        </a>
                                        <span class="d-block" style="font-size:0.8rem; color:#7a9e9a;">{{ __($cart->product->serviceCategory->name) }}</span>
                                        <span class="d-block fw-bold" style="font-size:0.85rem;">{{ @$cart->domain }}</span>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-0" style="color:#3FD1C0; font-weight:800;">{{ showAmount(@$cart->price) }}</h6>
                                        <span class="d-block" style="font-size:0.78rem; color:#7a9e9a;">{{ @billingCycle($cart->billing_cycle, true)['showText'] }}</span>
                                        <span class="d-block" style="font-size:0.75rem; color:#7a9e9a;">{{ gs('cur_sym') }}{{ showAmount(@$cart->setup_fee) }} @lang('Setup Fee')</span>
                                        <span style="font-size:0.8rem; font-weight:700; color:#0f2421;">@lang('Total') {{ showAmount(@$cart->total) }}</span>
                                    </div>
                                @else
                                    <div>
                                        @if ($cart->type == 4)
                                            <h6 class="d-inline me-2" style="font-weight:800;">@lang('Domain Renew')</h6>
                                            <a href="{{ route('user.domain.details', $cart->domain_id) }}" class="text--primary" style="font-size:0.8rem; font-weight:700;">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                        @else
                                            <h6 class="d-inline me-2" style="font-weight:800;">@lang('Domain Registration')</h6>
                                            <a href="{{ route('shopping.cart.config.domain', $cart->id) }}" class="text--primary" style="font-size:0.8rem; font-weight:700;">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                        @endif
                                        <span class="d-block fw-bold mt-1" style="font-size:0.875rem;">
                                            {{ @$cart->domain }} — {{ @$cart->reg_period }} @lang('Year')
                                            {{ @$cart->id_protection ? __('with ID Protection') : null }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-0" style="color:#3FD1C0; font-weight:800;">{{ showAmount(@$cart->price) }}</h6>
                                        @if (@$cart->id_protection)
                                            <span class="d-block" style="font-size:0.75rem; color:#7a9e9a;">{{ showAmount(@$cart->setup_fee) }} @lang('ID Protection')</span>
                                        @endif
                                        <span style="font-size:0.8rem; font-weight:700; color:#0f2421;">@lang('Total') {{ showAmount(@$cart->total) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-1 text-end">
                                <a class="remove_cart d-none" href="{{ route('shopping.cart.remove', $cart->id) }}" style="font-size:0.8rem; color:#ef4444; font-weight:700;">
                                    <i class="la la-trash"></i> @lang('Remove')
                                </a>
                                <a href="{{ route('shopping.cart.remove', $cart->id) }}" class="remove_icon" style="color:#ef4444; font-size:1.1rem;">
                                    <i class="la la-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-4 text-center">
                    <x-empty-message div={{ true }} message="Empty carts" />
                </div>
            @endforelse

            @if (@$cart)
                <div class="row mt-3">
                    <div class="col-lg-12 text-end mb-3">
                        <a href="{{ route('shopping.cart.empty') }}" class="btn btn--sm" style="background:rgba(239,68,68,0.10); color:#ef4444; border:1.5px solid rgba(239,68,68,0.25); border-radius:999px; font-weight:700;">
                            <i class="la la-trash"></i> @lang('Empty Cart')
                        </a>
                    </div>
                    <div class="col-lg-12">
                        <div class="card p-3">
                            @if (@$appliedCoupon)
                                <form action="{{ route('shopping.cart.coupon.remove') }}" method="post">
                                    @csrf
                                    <div class="text-center p-2 mb-3" style="background:rgba(63,209,192,0.08); border:1px solid rgba(63,209,192,0.20); border-radius:10px; font-size:0.875rem; font-weight:700; color:#0f2421;">
                                        {{ $appliedCoupon->coupon->code }} —
                                        {{ $appliedCoupon->coupon_type == 0 ? showAmount($appliedCoupon->coupon_discount, currencyFormat:false) . '%' : showAmount($carts->sum('discount')) }}
                                        @lang('Discount')
                                    </div>
                                    <button type="submit" class="btn btn--base w-100" style="background:rgba(245,158,11,0.12); color:#92400e; border-color:rgba(245,158,11,0.30);">
                                        @lang('Remove Coupon Code')
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('shopping.cart.coupon') }}" method="post">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control form--control h-45" name="coupon_code"
                                               placeholder="@lang('Enter coupon code if you have one')" required>
                                    </div>
                                    <button type="submit" class="btn btn--base btn--sm w-100">@lang('Validate Code')</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-4">
            <div class="card p-0" style="border-top: 3px solid #3FD1C0; position:sticky; top:90px;">
                <div class="card-header text-center" style="font-weight:800; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; color:#3FD1C0;">
                    @lang('Order Summary')
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                        <span style="color:#7a9e9a;">@lang('Subtotal')</span>
                        <span class="basicPrices fw-bold" style="color:#0f2421;">{{ showAmount($carts->sum('total')) }}</span>
                    </div>

                    @if ($appliedCoupon)
                        <div class="d-flex justify-content-between mb-2" style="font-size:0.855rem;">
                            <span class="discounts" style="color:#22c55e; font-weight:700;">
                                @lang('Discount') — {{ $appliedCoupon->coupon_type == 0 ? showAmount($appliedCoupon->coupon_discount, currencyFormat:false).'%' : showAmount($cart->sum('discount')) }}
                            </span>
                            <span class="discountAmounts" style="color:#22c55e; font-weight:700;">-{{ showAmount($cart->sum('discount')) }}</span>
                        </div>
                    @endif

                    <div style="height:1px; background:rgba(63,209,192,0.14); margin:0.75rem 0;"></div>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-weight:800; color:#0f2421;">@lang('Total')</h5>
                        <h5 class="mb-0" style="font-weight:900; color:#3FD1C0;">
                            <span class="finalAmounts">{{ showAmount($carts->sum('after_discount')) }}</span>
                        </h5>
                    </div>

                    @if (count($carts))
                        <form action="{{ route('user.invoice.create') }}" method="post" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn--base w-100" style="padding:0.75rem; font-size:0.95rem; font-weight:800;">
                                @lang('Checkout') <i class="la la-arrow-circle-right ms-1"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.cart_child:nth-child(odd) .card-body {
    background: rgba(63,209,192,0.03);
}
@media (max-width: 767px) {
    .remove_cart { display: inline-block !important; }
    .remove_icon { display: none; }
}
</style>
@endpush