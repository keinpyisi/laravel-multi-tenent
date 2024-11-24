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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Include additional JS from child view if provided -->
    @if (isset($header_js_defines) && !empty($header_js_defines))
        @vite($header_js_defines)
    @endif
    @if (isset($header_css_defines) && !empty($header_css_defines))
        @vite($header_css_defines)
    @endif
    <!-- Styles -->
    @livewireStyles
</head>

<body class="bg-gray-100 h-screen flex justify-center items-center">

    <!-- Login Card -->
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">

        <h2 class="text-2xl font-semibold text-center mb-6">ログイン</h2>

        <form action="{{ route('tenant.users.check_login') }}" method="POST">
            @csrf
            <!-- Email Input -->
            <div class="mb-4">
                <input type="text" id="login_id" name="login_id"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="ログインID(半角英数)" required>
            </div>
            <!-- Password Input -->
            <div class="mb-6">
                <input type="password" id="password" name="password"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="パスワード(半角英数)" required>
            </div>
            <!-- Login Button -->
            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">ログイン</button>
        </form>

    </div>

</body>

</html>
