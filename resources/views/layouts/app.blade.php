<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ilmora - School Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">
    @auth
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">Ilmora</a>
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">Dashboard</a>
            <a href="{{ route('groups') }}" class="text-sm text-gray-600 hover:text-indigo-600">Groups</a>
            <a href="{{ route('students') }}" class="text-sm text-gray-600 hover:text-indigo-600">Students</a>
            <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
            </form>
        </div>
    </nav>
    @endauth
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>
    @livewireScripts
</body>

</html>