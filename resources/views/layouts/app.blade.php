<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seoTitle ?? 'Progressive Movement Foundation Shop' }}</title>
    <meta name="description" content="{{ $seoDescription ?? 'ร้านค้าออนไลน์ Progressive Movement Foundation Shop รวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ จัดส่งทั่วไทย' }}">
    <link rel="canonical" href="{{ $seoCanonical ?? url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $seoOgType ?? 'website' }}">
    <meta property="og:title" content="{{ $seoTitle ?? 'Progressive Movement Foundation Shop' }}">
    <meta property="og:description" content="{{ $seoDescription ?? 'ร้านค้าออนไลน์ Progressive Movement Foundation Shop รวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ จัดส่งทั่วไทย' }}">
    <meta property="og:url" content="{{ $seoCanonical ?? url()->current() }}">
    <meta property="og:site_name" content="PGMF Shop">
    @if(!empty($seoImage))
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle ?? 'PGMF Shop' }}">
    @else
    <meta property="og:image" content="{{ asset('images/pgmf-logo.jpg') }}">
    @endif
    <meta property="og:locale" content="th_TH">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle ?? 'Progressive Movement Foundation Shop' }}">
    <meta name="twitter:description" content="{{ $seoDescription ?? 'ร้านค้าออนไลน์ Progressive Movement Foundation Shop รวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ จัดส่งทั่วไทย' }}">
    @if(!empty($seoImage))
    <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    @stack('seo')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased min-h-screen flex flex-col">
    <!-- Global Livewire Loading Bar -->
    <div x-data="{ loading: false }"
         x-on:livewire:navigating.window="loading = true"
         x-on:livewire:navigated.window="loading = false"
         x-init="
            Livewire.hook('request', ({ respond, fail }) => {
                loading = true;
                respond(() => { loading = false; });
                fail(() => { loading = false; });
            });
         "
         x-show="loading"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-0 left-0 right-0 z-[9999]"
         style="display: none;">
        <div class="h-0.5 bg-[hsl(var(--primary))]/20 overflow-hidden">
            <div class="h-full bg-[hsl(var(--primary))] animate-loading-bar"></div>
        </div>
    </div>

    <livewire:layout.navbar />
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>
    @include('partials.footer')
    @livewireScripts
    <style>
        .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem; pointer-events: none; }
        .toast-item { pointer-events: auto; display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.25rem; border-radius: 0.625rem; font-size: 0.875rem; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,.08), 0 1px 3px rgba(0,0,0,.06); transform: translateX(120%); opacity: 0; transition: transform 0.5s cubic-bezier(.21,1.02,.73,1), opacity 0.4s ease; position: relative; overflow: hidden; min-width: 280px; max-width: 420px; border: 1px solid; }
        .toast-item.show { transform: translateX(0); opacity: 1; }
        .toast-item.hide { transform: translateX(120%); opacity: 0; }
        .toast-item.success { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .toast-item.error { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .toast-item.info { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        .toast-icon { flex-shrink: 0; width: 1.5rem; height: 1.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .toast-item.success .toast-icon { background: #dcfce7; }
        .toast-item.error .toast-icon { background: #fee2e2; }
        .toast-item.info .toast-icon { background: #dbeafe; }
        .toast-icon svg { width: 0.875rem; height: 0.875rem; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
        .toast-item.success .toast-icon svg { stroke: #16a34a; }
        .toast-item.error .toast-icon svg { stroke: #dc2626; }
        .toast-item.info .toast-icon svg { stroke: #2563eb; }
        .toast-icon svg .check-path { stroke-dasharray: 20; stroke-dashoffset: 20; animation: drawCheck 0.4s 0.3s ease forwards; }
        .toast-icon svg .x-path { stroke-dasharray: 12; stroke-dashoffset: 12; animation: drawCheck 0.3s 0.3s ease forwards; }
        @keyframes drawCheck { to { stroke-dashoffset: 0; } }
        .toast-progress { position: absolute; bottom: 0; left: 0; height: 2px; border-radius: 0 0 0.625rem 0.625rem; animation: progressShrink 3.5s linear forwards; }
        .toast-item.success .toast-progress { background: #16a34a; }
        .toast-item.error .toast-progress { background: #dc2626; }
        .toast-item.info .toast-progress { background: #2563eb; }
        @keyframes progressShrink { from { width: 100%; } to { width: 0%; } }
        .toast-close { flex-shrink: 0; cursor: pointer; opacity: .4; transition: opacity .2s; background: none; border: none; padding: 0.25rem; margin-left: 0.5rem; }
        .toast-item.success .toast-close { color: #166534; }
        .toast-item.error .toast-close { color: #991b1b; }
        .toast-item.info .toast-close { color: #1e40af; }
        .toast-close:hover { opacity: .8; }
    </style>
    <script>
        // Payment countdown timer (Alpine.js component)
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentCountdown', (deadline) => ({
                hours: '00',
                minutes: '00',
                seconds: '00',
                expired: false,
                interval: null,
                init() {
                    this.tick();
                    this.interval = setInterval(() => this.tick(), 1000);
                },
                tick() {
                    const now = new Date().getTime();
                    const end = new Date(deadline).getTime();
                    const diff = end - now;
                    if (diff <= 0) {
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        this.expired = true;
                        if (this.interval) clearInterval(this.interval);
                        return;
                    }
                    const h = Math.floor(diff / (1000 * 60 * 60));
                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((diff % (1000 * 60)) / 1000);
                    this.hours = String(h).padStart(2, '0');
                    this.minutes = String(m).padStart(2, '0');
                    this.seconds = String(s).padStart(2, '0');
                },
                destroy() {
                    if (this.interval) clearInterval(this.interval);
                }
            }));
        });

        // Toast notification system
        (function() {
            let container;
            function getContainer() {
                if (!container || !document.body.contains(container)) {
                    container = document.createElement('div');
                    container.className = 'toast-container';
                    document.body.appendChild(container);
                }
                return container;
            }

            function getIcon(type) {
                if (type === 'success') return '<svg viewBox="0 0 24 24"><path class="check-path" d="M5 13l4 4L19 7"/></svg>';
                if (type === 'error') return '<svg viewBox="0 0 24 24"><path class="x-path" d="M18 6L6 18"/><path class="x-path" d="M6 6l12 12"/></svg>';
                return '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path class="check-path" d="M12 8v4m0 4h.01"/></svg>';
            }

            window.toast = function(message, type = 'success') {
                const c = getContainer();
                const el = document.createElement('div');
                el.className = 'toast-item ' + type;
                el.innerHTML = `
                    <div class="toast-icon">${getIcon(type)}</div>
                    <span style="flex:1;line-height:1.4">${message}</span>
                    <button class="toast-close" onclick="this.parentElement.classList.replace('show','hide');setTimeout(()=>this.parentElement.remove(),500)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                    <div class="toast-progress"></div>
                `;
                c.appendChild(el);
                requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
                setTimeout(() => {
                    if (el.parentElement) {
                        el.classList.replace('show', 'hide');
                        setTimeout(() => el.remove(), 500);
                    }
                }, 3500);
            };

            let registered = false;
            function registerToastListener() {
                if (registered || typeof Livewire === 'undefined') return false;
                registered = true;
                Livewire.on('toast', (...args) => {
                    let msg = '', type = 'success';
                    if (args[0] && typeof args[0] === 'object' && !Array.isArray(args[0]) && args[0].message) {
                        msg = args[0].message; type = args[0].type || 'success';
                    } else if (Array.isArray(args[0]) && args[0][0]?.message) {
                        msg = args[0][0].message; type = args[0][0].type || 'success';
                    } else if (typeof args[0] === 'string') {
                        msg = args[0]; type = args[1] || 'success';
                    }
                    if (msg) window.toast(msg, type);
                });
                return true;
            }
            document.addEventListener('livewire:init', () => registerToastListener());
            document.addEventListener('livewire:initialized', () => registerToastListener());
            if (typeof Livewire !== 'undefined') {
                registerToastListener();
            } else {
                let attempts = 0;
                const poll = setInterval(() => {
                    attempts++;
                    if (registerToastListener() || attempts > 50) clearInterval(poll);
                }, 200);
            }
        })();
    </script>
</body>
</html>
