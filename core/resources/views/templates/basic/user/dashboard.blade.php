@extends($activeTemplate . 'layouts.master_side_bar')

@section('content')
<div class="col-lg-9">

    <div class="notice mb-3"></div>

    @if ($user->kv == 0 || $user->kv == 2)
        @php $kyc = @getContent('kyc.content', true); @endphp
        <div class="card mb-4 bg--navajowhite">
            <div class="card-body">
                @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                    <div class="d-flex justify-content-between flex-wrap align-items-center gap-3 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0">@lang('KYC Documents Rejected')</h6>
                            <button class="btn btn--base btn--xs" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                        </div>
                        <a href="{{ route('user.kyc.form') }}" class="fw-bold text--primary" style="font-size:0.875rem;">@lang('Click Here to Re-submit Documents')</a>
                    </div>
                    <p style="font-size:0.875rem;">{{ __(@$kyc->data_values->kyc_reject) }}</p>
                @elseif($user->kv == Status::KYC_UNVERIFIED)
                    <div class="d-flex justify-content-between flex-wrap align-items-center gap-2 mb-2">
                        <h6 class="mb-0">@lang('KYC Verification Required')</h6>
                        <a href="{{ route('user.kyc.form') }}" class="fw-bold text--primary" style="font-size:0.875rem;">@lang('Click Here to Submit Documents')</a>
                    </div>
                    <p style="font-size:0.875rem;">{{ __(@$kyc->data_values->kyc_required) }}</p>
                @elseif($user->kv == Status::KYC_PENDING)
                    <div class="d-flex justify-content-between flex-wrap align-items-center gap-2 mb-2">
                        <h6 class="mb-0">@lang('KYC Verification Pending')</h6>
                        <a href="{{ route('user.kyc.data') }}" class="fw-bold text--primary" style="font-size:0.875rem;">@lang('See KYC Data')</a>
                    </div>
                    <p style="font-size:0.875rem;">{{ __(@$kyc->data_values->kyc_pending) }}</p>
                @endif
            </div>
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="row user-dashboard g-3 mb-4">

        @php
        $stats = [
            ['label'=>'Balance',  'value'=>showAmount($user->balance),        'icon'=>'fas fa-wallet',      'route'=>route('user.transactions'),   'gradient'=>'blooker'],
            ['label'=>'Deposit',  'value'=>@$user->deposits->count(),         'icon'=>'fas fa-money-bill-wave','route'=>route('user.deposit.history'),'gradient'=>'blooker'],
            ['label'=>'Services', 'value'=>$totalService,                     'icon'=>'fa fa-box',          'route'=>route('user.service.list'),   'gradient'=>'scooter'],
            ['label'=>'Domains',  'value'=>$totalDomain,                      'icon'=>'fas fa-globe',       'route'=>route('user.domain.list'),    'gradient'=>'bloody'],
            ['label'=>'Tickets',  'value'=>$totalTicket,                      'icon'=>'fa fa-comments',     'route'=>route('ticket.index'),        'gradient'=>'ohhappiness'],
            ['label'=>'Invoices', 'value'=>$totalInvoice,                     'icon'=>'fas fa-credit-card', 'route'=>route('user.invoice.list'),   'gradient'=>'blooker'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="custom--card custom-radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-1" style="font-size:0.78rem; font-weight:700; color:#7a9e9a; text-transform:uppercase; letter-spacing:0.5px;">@lang($stat['label'])</p>
                            <h4 class="my-0" style="color:#0f2421; font-weight:800;">{{ $stat['value'] }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded--circle bg-gradient-{{ $stat['gradient'] }} ms--auto">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <a href="{{ $stat['route'] }}" class="has-anchor"></a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{-- ── Info Cards ── --}}
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card h-100" style="border-top: 3px solid rgba(239,68,68,0.35);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span style="font-size:0.875rem; font-weight:700; color:#0f2421;"><i class="fas fa-calculator me-1" style="color:#3FD1C0;"></i>@lang('Overdue Invoices')</span>
                        <a class="btn btn--base btn--xs" href="{{ route('user.invoice.list') }}">
                            <i class="fas fa-list"></i> @lang('View All')
                        </a>
                    </div>
                    <p style="font-size:0.875rem;">
                        @lang('You have') <strong>{{ $totalOverDueInvoice->total }}</strong>
                        @lang('overdue invoice(s) with a total balance due of')
                        <strong>{{ showAmount($totalOverDueInvoice->totalDue) }}</strong>.
                        @lang('Pay them now to avoid any interruptions in service').
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100" style="border-top: 3px solid rgba(63,209,192,0.35);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span style="font-size:0.875rem; font-weight:700; color:#0f2421;"><i class="fas fa-cube me-1" style="color:#3FD1C0;"></i>@lang('Products/Services')</span>
                        <a class="btn btn--base btn--xs" href="{{ route('user.service.list') }}"><i class="fas fa-list"></i> @lang('View All')</a>
                    </div>
                    <p style="font-size:0.875rem;">
                        @lang('It appears you do not have any products/services with us yet').
                        <a href="{{ route('service.category') }}?all" class="text--primary fw-bold">@lang('Place an order to get started')</a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100" style="border-top: 3px solid rgba(63,209,192,0.35);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span style="font-size:0.875rem; font-weight:700; color:#0f2421;"><i class="fas fa-comments me-1" style="color:#3FD1C0;"></i>@lang('Support Tickets')</span>
                        <a class="btn btn--base btn--xs" href="{{ route('ticket.index') }}"><i class="fas fa-list"></i> @lang('View All')</a>
                    </div>
                    <p style="font-size:0.875rem;">
                        @lang('No Recent Tickets Found. If you need any help'),
                        <a href="{{ route('ticket.open') }}" class="text--primary fw-bold">@lang('please open a ticket')</a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100" style="border-top: 3px solid rgba(63,209,192,0.35);">
                <div class="card-body">
                    <p style="font-size:0.875rem; font-weight:700; color:#0f2421;" class="mb-3"><i class="fas fa-globe me-1" style="color:#3FD1C0;"></i>@lang('Register New Domain')</p>
                    <form action="" class="form">
                        <div class="form-group position-relative mb-0">
                            <div class="domain-search-icon"><i class="fas fa-search"></i></div>
                            <input class="form-control form--control h-45" type="text" name="domain"
                                placeholder="@lang('Domain name or keyword')" required
                                style="padding-left: 2.75rem; padding-right: 7rem;">
                            <div class="domain-search-icon-reset">
                                <button class="btn btn--base btn--sm" type="submit">@lang('Search')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
    <div class="modal fade" id="kycRejectionReason">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">@lang('KYC Document Rejection Reason')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ $user->kyc_rejection_reason }}</p>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('script')
<script>
(function($) {
    "use strict";
    $('.form').on('submit', function(e) {
        e.preventDefault();
        var domain = $(this).find('input[name=domain]').val();
        window.location.href = "{{ route('register.domain') }}?domain=" + domain;
    });
})(jQuery);
</script>
@endpush