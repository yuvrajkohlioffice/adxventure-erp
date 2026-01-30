<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <x-admin.style></x-admin.style>
    
    @yield('css')
    @livewireStyles
</head>

<body class="bg-light">

    <x-admin.header></x-admin.header>
    <x-admin.sidebar></x-admin.sidebar>

    <main id="main" class="main min-vh-100 py-4">
        <div class="container-fluid">
            {{ $slot }}
        </div>
    </main>

    <x-admin.footer></x-admin.footer>

    <div id="settingsOverlay" class="settings-overlay"></div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <x-admin.script></x-admin.script>

    <script>
        $(document).ready(function() {
            // Live Time Update for Header
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                $('#live-time').text(timeString);
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Settings Sidebar Logic
            const $settingsSidebar = $('#settingsSidebar');
            const $settingsOverlay = $('#settingsOverlay');

            $('#settingsToggle').on('click', function() {
                $settingsSidebar.addClass('show');
                $settingsOverlay.fadeIn(200);
            });

            function closeSettings() {
                $settingsSidebar.removeClass('show');
                $settingsOverlay.fadeOut(200);
            }

            $('#settingsClose, #settingsOverlay').on('click', closeSettings);

            // Toastr Configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
            };

            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>

    @yield('script')
    @livewireScripts
</body>
</html>