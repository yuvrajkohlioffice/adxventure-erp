<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
        <link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
        <x-admin.style></x-admin.style>
        
        @yield('css')
    </head>
        <x-admin.header></x-admin.header>
        <x-admin.sidebar></x-admin.sidebar>

        <main id="main" class="main">
            {{ $slot }}
        </main>

        <x-admin.footer></x-admin.footer>
        <x-admin.script></x-admin.script>
        <script>
            const settingsToggle = document.getElementById('settingsToggle');
            const settingsSidebar = document.getElementById('settingsSidebar');
            const settingsOverlay = document.getElementById('settingsOverlay');
            const settingsClose = document.getElementById('settingsClose');

            settingsToggle.addEventListener('click', () => {
                settingsSidebar.classList.add('show');
                settingsOverlay.classList.add('show');
            });

            function closeSettings() {
                settingsSidebar.classList.remove('show');
                settingsOverlay.classList.remove('show');
            }

            settingsClose.addEventListener('click', closeSettings);
            settingsOverlay.addEventListener('click', closeSettings);
        </script>

        @yield('script')
    </body>
</html>