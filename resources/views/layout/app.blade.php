<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Topbar -->
    <div class="navbar bg-white shadow-sm border-b border-gray-200">
        <div class="flex-1">
            <a href="/" class="text-xl font-bold text-indigo-600 pl-6">
                {{ config('app.name') }}
            </a>
        </div>
        <div class="flex-none gap-4 pr-6">
            <!-- User Info -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" class="btn btn-ghost btn-circle avatar online cursor-pointer">
                    <div class="w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-white rounded-box w-52 border border-gray-200">
                    <li class="menu-title">
                        <span>{{ Auth::user()->name }}</span>
                    </li>
                    <li>
                        <span class="text-xs text-gray-500">{{ Auth::user()->role }}</span>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="p-0">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen">
        @yield('content')
    </div>
</body>
</html>
