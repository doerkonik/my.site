@extends($activeTemplate . 'layouts.frontend')

@section('content')

@push('style')
<style>
    /* ─── Sidebar Layout Shell ─── */
    .dtech-service-layout {
        min-height: 100vh;
        padding: 2rem 0 3rem;
        background: hsl(var(--light));
        font-family: var(--body-font, 'Manrope', sans-serif);
    }

    /* ─── Sidebar Panel ─── */
    .dtech-sidebar {
        position: sticky;
        top: 88px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Glass sidebar nav block */
    .dtech-sidebar-block {
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(63, 209, 192, 0.18);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(63, 209, 192, 0.09), 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .dtech-sidebar-block:hover {
        box-shadow: 0 10px 40px rgba(63, 209, 192, 0.14), 0 2px 12px rgba(0,0,0,0.06);
    }

    /* Block header */
    .dtech-sidebar-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.1rem;
        background: linear-gradient(135deg, rgba(63,209,192,0.12) 0%, rgba(63,209,192,0.04) 100%);
        border-bottom: 1px solid rgba(63,209,192,0.15);
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        color: #3FD1C0;
    }

    .dtech-sidebar-title i {
        font-size: 1rem;
        opacity: 0.8;
    }

    /* Nav links */
    .dtech-sidebar-links {
        display: flex;
        flex-direction: column;
        padding: 0.4rem;
        gap: 0.15rem;
    }

    .dtech-sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 0.85rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: hsl(var(--body, 220 15% 40%));
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.22s ease;
        position: relative;
    }

    .dtech-sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%) scaleY(0);
        width: 3px;
        height: 60%;
        background: #3FD1C0;
        border-radius: 0 3px 3px 0;
        transition: transform 0.22s ease;
    }

    .dtech-sidebar-link:hover {
        color: #3FD1C0;
        background: rgba(63, 209, 192, 0.08);
    }

    .dtech-sidebar-link:hover::before {
        transform: translateY(-50%) scaleY(1);
    }

    /* Active state */
    .dtech-sidebar-link.active {
        color: #3FD1C0;
        background: rgba(63, 209, 192, 0.10);
        font-weight: 700;
    }

    .dtech-sidebar-link.active::before {
        transform: translateY(-50%) scaleY(1);
    }

    .dtech-sidebar-link .link-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(63, 209, 192, 0.35);
        flex-shrink: 0;
        transition: background 0.22s;
    }

    .dtech-sidebar-link:hover .link-dot,
    .dtech-sidebar-link.active .link-dot {
        background: #3FD1C0;
    }

    /* ─── Mobile Sidebar Toggle Button ─── */
    .dtech-mobile-toggle {
        display: none;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.1rem;
        font-size: 0.875rem;
        font-weight: 700;
        color: #3FD1C0;
        background: rgba(63, 209, 192, 0.08);
        border: 1.5px solid rgba(63, 209, 192, 0.28);
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.22s ease;
        width: fit-content;
        margin-bottom: 1rem;
    }

    .dtech-mobile-toggle:hover {
        background: rgba(63, 209, 192, 0.14);
        border-color: #3FD1C0;
        box-shadow: 0 4px 16px rgba(63, 209, 192, 0.20);
    }

    /* ─── Mobile Drawer ─── */
    .dtech-mobile-sidebar {
        display: none;
        flex-direction: column;
        gap: 0.75rem;
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.38s ease, opacity 0.28s ease;
        opacity: 0;
        margin-bottom: 0;
    }

    .dtech-mobile-sidebar.open {
        max-height: 600px;
        opacity: 1;
        margin-bottom: 1.25rem;
    }

    /* ─── Close btn inside sidebar (mobile) ─── */
    .collapable-sidebar__close {
        display: none;
        position: absolute;
        right: 10px;
        top: 10px;
        background: rgba(63,209,192,0.10);
        border: 1px solid rgba(63,209,192,0.25);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        align-items: center;
        justify-content: center;
        color: #3FD1C0;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .collapable-sidebar__close:hover {
        background: rgba(63,209,192,0.20);
    }

    /* ─── Content area gutter ─── */
    .dtech-content-area {
        min-width: 0; /* prevents flex overflow */
    }

    /* ─── Responsive ─── */
    @media (max-width: 991px) {
        .dtech-desktop-sidebar { display: none !important; }
        .dtech-mobile-toggle   { display: flex; }
        .dtech-mobile-sidebar  { display: flex; }
        .dtech-service-layout  { padding: 1.25rem 0 2rem; }
    }

    @media (min-width: 992px) {
        .dtech-mobile-toggle  { display: none !important; }
        .dtech-mobile-sidebar { display: none !important; }
    }

    /* ─── Show sidebar bar override (keep for JS compat) ─── */
    .show-sidebar-bar { display: none; }
</style>
@endpush

<div class="dtech-service-layout">
    <div class="container px-3">
        <div class="row gy-3">

            {{-- ══════════════════════════════════════
                 DESKTOP SIDEBAR (lg and up)
            ══════════════════════════════════════ --}}
            <div class="col-lg-3 dtech-desktop-sidebar">
                <aside class="dtech-sidebar">

                    {{-- Service Categories --}}
                    <div class="dtech-sidebar-block">
                        <div class="dtech-sidebar-title">
                            <i class="las la-th-list"></i>
                            @lang('Service Categories')
                        </div>
                        <nav class="dtech-sidebar-links" id="categoryMenu">
                            @foreach ($serviceCategories as $category)
                                <a href="{{ route('service.category', $category->slug) }}"
                                   class="dtech-sidebar-link"
                                   data-slug="{{ $category->slug }}">
                                    <span class="link-dot"></span>
                                    {{ __($category->name) }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Actions --}}
                    <div class="dtech-sidebar-block">
                        <div class="dtech-sidebar-title">
                            <i class="las la-bolt"></i>
                            @lang('Actions')
                        </div>
                        <nav class="dtech-sidebar-links" id="actionMenu">
                            <a href="{{ route('register.domain') }}" class="dtech-sidebar-link">
                                <span class="link-dot"></span>
                                @lang('Register New Domain')
                            </a>
                            <a href="{{ route('shopping.cart') }}" class="dtech-sidebar-link">
                                <span class="link-dot"></span>
                                @lang('View Cart')
                            </a>
                        </nav>
                    </div>

                </aside>
            </div>

            {{-- ══════════════════════════════════════
                 MOBILE: Toggle + Collapsible Sidebar
            ══════════════════════════════════════ --}}
            <div class="col-12 d-lg-none">

                {{-- Toggle button --}}
                <button class="dtech-mobile-toggle" id="dtechSidebarToggle" aria-expanded="false">
                    <i class="las la-bars"></i>
                    @lang('Browse Categories')
                </button>

                {{-- Mobile sidebar drawer --}}
                <div class="dtech-mobile-sidebar" id="dtechMobileSidebar" aria-hidden="true">

                    {{-- Service Categories --}}
                    <div class="dtech-sidebar-block">
                        <div class="dtech-sidebar-title">
                            <i class="las la-th-list"></i>
                            @lang('Service Categories')
                        </div>
                        <nav class="dtech-sidebar-links" id="categoryMenuMobile">
                            @foreach ($serviceCategories as $category)
                                <a href="{{ route('service.category', $category->slug) }}"
                                   class="dtech-sidebar-link"
                                   data-slug="{{ $category->slug }}">
                                    <span class="link-dot"></span>
                                    {{ __($category->name) }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Actions --}}
                    <div class="dtech-sidebar-block">
                        <div class="dtech-sidebar-title">
                            <i class="las la-bolt"></i>
                            @lang('Actions')
                        </div>
                        <nav class="dtech-sidebar-links" id="actionMenuMobile">
                            <a href="{{ route('register.domain') }}" class="dtech-sidebar-link">
                                <span class="link-dot"></span>
                                @lang('Register New Domain')
                            </a>
                            <a href="{{ route('shopping.cart') }}" class="dtech-sidebar-link">
                                <span class="link-dot"></span>
                                @lang('View Cart')
                            </a>
                        </nav>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════
                 MAIN CONTENT YIELD
            ══════════════════════════════════════ --}}
            <div class="col-lg-9 dtech-content-area">
                @yield('data')
            </div>

        </div>
    </div>
</div>

@endsection

@push('script')
<script>
'use strict';
(function ($) {

    /* ── Active state: Category menu ── */
    var currentSlug = "{{ @$serviceCategory->slug }}";
    var currentUrl  = "{{ url()->current() }}";

    // Desktop
    $('#categoryMenu a[data-slug="' + currentSlug + '"]').addClass('active');
    $('#actionMenu  a[href="' + currentUrl + '"]').addClass('active');

    // Mobile mirrors
    $('#categoryMenuMobile a[data-slug="' + currentSlug + '"]').addClass('active');
    $('#actionMenuMobile   a[href="' + currentUrl + '"]').addClass('active');

    /* ── Mobile sidebar toggle ── */
    var $toggle  = $('#dtechSidebarToggle');
    var $drawer  = $('#dtechMobileSidebar');

    $toggle.on('click', function () {
        var isOpen = $drawer.hasClass('open');

        if (isOpen) {
            $drawer.removeClass('open');
            $toggle.attr('aria-expanded', 'false');
            $drawer.attr('aria-hidden', 'true');
            $toggle.find('i').removeClass('la-times').addClass('la-bars');
        } else {
            $drawer.addClass('open');
            $toggle.attr('aria-expanded', 'true');
            $drawer.attr('aria-hidden', 'false');
            $toggle.find('i').removeClass('la-bars').addClass('la-times');
        }
    });

    /* ── Close drawer on outside click ── */
    $(document).on('click', function (e) {
        if (!$toggle.is(e.target) && !$toggle.has(e.target).length &&
            !$drawer.is(e.target)  && !$drawer.has(e.target).length) {
            if ($drawer.hasClass('open')) {
                $drawer.removeClass('open');
                $toggle.attr('aria-expanded', 'false');
                $drawer.attr('aria-hidden', 'true');
                $toggle.find('i').removeClass('la-times').addClass('la-bars');
            }
        }
    });

})(jQuery);
</script>
@endpush