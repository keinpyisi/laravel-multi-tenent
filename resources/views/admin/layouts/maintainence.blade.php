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
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center space-y-6">
            <!-- Icon -->
            <div class="text-gray-500">

            </div>
            <!-- Heading -->
            <h1 class="text-3xl font-bold">メインテナンス中です</h1>
            <!-- Message -->
            <p class="text-gray-600">{!!$config['text']!!}</p>
        </div>
    </div>
    @livewireScriptConfig
</body>

</html>