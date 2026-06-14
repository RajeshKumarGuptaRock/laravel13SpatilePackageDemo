<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Receipt') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen">
<div class="flex">
    @auth
       

        <div class="flex-1">
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Receipt')</h1>
                    </div>
                    
                </div>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    @else
        <main class="w-full">
            @yield('content')
        </main>
    @endauth
</div>
</body>
</html>

