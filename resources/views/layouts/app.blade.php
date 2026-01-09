<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- CSS DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gradient-to-br from-purple-700 to-blue-500 min-h-screen text-white">


    <x-banner />

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 min-h-screen text-white">
            @livewire('navigation-menu')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col p-6">
            @if (isset($header))
            <header class="mb-6">
                <h2 class="text-2xl font-bold text-white">
                    {{ $header }}
                </h2>
            </header>
            @endif

            <main class="bg-white rounded-xl p-6 shadow-md">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')
    @livewireScripts

    {{-- jQuery & DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    {{-- Scripts tambahan dari halaman --}}
    @stack('scripts')
</body>
</html>

