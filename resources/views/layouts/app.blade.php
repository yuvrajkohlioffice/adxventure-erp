<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Show Counts  -->
    <style>
        .chartjs.card {
            height: 335px;
        }
    </style>
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <x-admin.style></x-admin.style>

    @yield('css')
    @livewireStyles
</head>

<body class="bg-light">

    <x-admin.header></x-admin.header>
    <x-admin.sidebar></x-admin.sidebar>

    <main id="main" class="main min-vh-100">
        


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

<script type="module">
    // "type=module" is CRITICAL. It forces this to wait for app.js to load.
    $(document).ready(function() {
        
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

    });
</script>
    @yield('script')
    @livewireScripts
</body>

</html>
