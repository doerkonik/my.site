@php
    $footer = @getContent('footer.content', true);
    $policyPages = @getContent('policy_pages.element', orderById:true);
@endphp

<footer class="py-5 footer bg-konik">
    <div class="container">
        <div class="footer-content text-center">
            <a href="{{ route('home') }}" class="logo d-inline-block mb-3">
                <img src="{{ siteLogo() }}" alt="@lang('logo')" style="height:38px;">
            </a>
            <p class="footer-text mx-auto mb-4">
                {{ __(@$footer->data_values->description) }}
            </p>
            <ul class="footer-links d-flex flex-wrap gap-3 justify-content-center list-unstyled mb-4">
                <li><a href="{{ route('home') }}" class="text--base fw-bold">@lang('Home')</a></li>
                <li><a href="{{ route('blogs') }}" class="text--base fw-bold">@lang('Announcements')</a></li>
                @foreach($policyPages as $policyPage)
                    <li>
                        <a href="{{ route('policy.pages', ['slug'=>slug($policyPage->data_values->title)]) }}" class="text--base fw-bold">
                            {{ __(@$policyPage->data_values->title) }}
                        </a>
                    </li>
                @endforeach
                <li><a href="{{ route('contact') }}" class="text--base fw-bold">@lang('Contact')</a></li>
                <li><a href="{{ route('user.login') }}" class="text--base fw-bold">@lang('Login')</a></li>
                <li><a href="{{ route('user.register') }}" class="text--base fw-bold">@lang('Register')</a></li>
            </ul>
            <p style="font-size:0.82rem; color:#7a9e9a; font-weight:600;">
                {{ gs('site_name') }} &copy; {{ date('Y') }}. @lang('All Rights Reserved')
            </p>
        </div>
    </div>
</footer>