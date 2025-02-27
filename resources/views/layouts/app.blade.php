<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'mONU') }}</title>


    <link rel="icon" type="image/png"
        href="{{ asset('https://raw.afripos.co.ke/afripos-logo-files/favicon/webp/afripos-favicon-color.webp') }}">

    <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    {{-- sweeet alerts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script
        src="https://www.paypal.com/sdk/js?client-id=Af-MUiyjW9p1k8e8xQWYMwSk_xZItfk40NGfJ2rV8VMnD5ww0KyoCQBANE_JnIyxx7y5Acu53JodoI9f&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card&locale=en_KE"
        data-sdk-integration-source="developer-studio"></script>
    {{-- <script
        src="https://www.paypal.com/sdk/js?client-id=ARz0uKx-U550i3paYyGQYIZEvDSRZB_AVPoHryZVbNDo8C2oom9W6DcHTf2W4MgvMbaWaNW4BNDh96Th&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"
        data-sdk-integration-source="developer-studio"></script> --}}

    {{-- <script
        src="https://www.paypal.com/sdk/js?client-id=ARz0uKx-U550i3paYyGQYIZEvDSRZB_AVPoHryZVbNDo8C2oom9W6DcHTf2W4MgvMbaWaNW4BNDh96Th&currency=USD&locale=en_KE">
    </script> --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.navigation')
        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>
        <footer class="bg-white shadow w-full">
            <div class="container mx-auto px-4">
                <div class="flex justify-center items-center py-4">
                    <div class="text-gray-600">
                        © {{ date('Y') }} AfriPOS. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
