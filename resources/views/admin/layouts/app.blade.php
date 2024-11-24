@props(['header_js_defines'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
    <script>
        window.Laravel = {
            success: @json(session('success')),
            error: @json(session('error'))
        };
        window.Lang = @json(__('lang')); // Assuming 'lang' is your language file
    </script>
    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <!-- Include additional JS from child view if provided -->
    @if (isset($header_js_defines) && !empty($header_js_defines))
        @vite($header_js_defines)
    @endif
    @if (isset($header_css_defines) && !empty($header_css_defines))
        @vite($header_css_defines)
    @endif
    @if (isset($header_js_variables) && !empty($header_js_variables))
        <script>
            (function() {
                var jsVars = @json($header_js_variables);
                for (var key in jsVars) {
                    if (jsVars.hasOwnProperty(key)) {
                        window[key] = jsVars[key];
                    }
                }
            })();
        </script>
    @endif
    <!-- Styles -->
    @livewireStyles

    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex min-h-screen overflow-hidden">

        <x-admin::app.sidebar :variant="$attributes['sidebarVariant']" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">
            <x-admin::app.header :variant="$attributes['headerVariant']" />

            <main class="grow">
                {{ $slot }}
            </main>

        </div>


    </div>


    @livewireScriptConfig


</body>
<footer class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 text-center text-surface/75  lg:text-left">

    <!--Copyright section-->
    <div class="bg-black/5 p-6 text-center">
        <span>Copyright © 2023</span>
        <a class="font-semibold" href="https://https://ascon.co.jp/">ASCON Co.,Ltd. All Rights Reserved.</a>
    </div>
</footer>

</html>
