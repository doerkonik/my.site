<div class="header">
    <div class="container">
        <div class="header-bottom">
            <div class="header-bottom-area align-items-center">

                {{-- Logo --}}
                <div class="logo">
                    <a href="http://localhost:3000/home">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')">
                    </a>
                </div>

                {{-- Center Menu --}}
                <ul class="menu mx-auto">
                    <li>
                        <a href="{{ route('register.domain') }}">@lang('Domain')</a>
                    </li>
                    <li>
                        <a href="http://localhost:3000/hosting">@lang('Hosting')</a>
                    </li>
                    <li>
                        <a href="{{ route('service.category') }}?all">@lang('VPS')</a>
                    </li>
                </ul>

                {{-- Right Section --}}
                <div class="d-flex align-items-center gap-2 ms-auto">

                    @auth
                        <a href="{{ route('user.home') }}" class="btn btn--base btn--sm">
                            <i class="las la-home"></i> @lang('Dashboard')
                        </a>
                        <a href="{{ route('user.logout') }}" class="btn btn--danger btn--sm">
                            <i class="las la-sign-out-alt"></i> @lang('Logout')
                        </a>
                    @else
                        <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">
                            <i class="las la-sign-in-alt"></i> @lang('Login')
                        </a>
                        <a href="{{ route('user.register') }}" class="btn btn--base btn--sm" style="background:transparent; color:#3FD1C0; border-color:#3FD1C0;">
                            @lang('Register')
                        </a>
                    @endauth

                    {{-- Cart --}}
                    @include($activeTemplate . 'partials.cart_widget')

                    {{-- Language --}}
                    <x-language />

                </div>

                {{-- Mobile Trigger --}}
                <div class="header-trigger-wrapper d-flex d-xl-none align-items-center ms-2">
                    <div class="header-trigger">
                        <div class="header-trigger__icon">
                            <i class="las la-bars"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>