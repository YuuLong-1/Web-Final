<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - User Management System (Laravel 13)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col font-sans" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Header -->
    <header class="bg-slate-800/90 border-b border-slate-700/80 sticky top-0 z-40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 group-hover:scale-105 transition">
                            <i class="fa-solid fa-users-gear text-lg"></i>
                        </div>
                        <div>
                            <span class="text-base font-bold text-white tracking-tight flex items-center gap-1.5">
                                User Management
                                <span class="text-[10px] uppercase tracking-wider font-semibold bg-indigo-500/20 text-indigo-400 px-1.5 py-0.5 rounded border border-indigo-500/30">L13</span>
                            </span>
                            <span class="text-[11px] text-slate-400 block -mt-0.5">Final Exam Project</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('users.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fa-solid fa-users mr-1.5"></i> Users Directory
                    </a>
                    <a href="{{ route('users.create') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.create') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fa-solid fa-user-plus mr-1.5"></i> Add User
                    </a>
                </nav>

                <!-- User profile / Session controls -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex items-center gap-3 pl-3 border-l border-slate-700">
                            <div class="w-9 h-9 rounded-full bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-white leading-none">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ Auth::user()->role->name ?? 'User' }}</div>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    title="Sign Out"
                                    class="p-2 rounded-lg bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/40 transition cursor-pointer text-xs font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-400 hover:text-white">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-700 bg-slate-800 px-4 py-3 space-y-2">
            <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:bg-slate-700">
                <i class="fa-solid fa-users mr-2"></i> Users Directory
            </a>
            <a href="{{ route('users.create') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:bg-slate-700">
                <i class="fa-solid fa-user-plus mr-2"></i> Add User
            </a>
            @auth
                <div class="pt-2 border-t border-slate-700 text-xs text-slate-400">
                    Logged in as <strong class="text-white">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Toast Alerts -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-emerald-300 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-emerald-200">Success!</h4>
                        <p class="text-xs text-emerald-300 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-emerald-400 hover:text-emerald-200">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-xl bg-rose-500/10 border border-rose-500/30 p-4 text-rose-300 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center">
                        <i class="fa-solid fa-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-rose-200">Notice</h4>
                        <p class="text-xs text-rose-300 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-rose-400 hover:text-rose-200">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-xl bg-rose-500/10 border border-rose-500/30 p-4 text-rose-300 shadow-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-rose-200">There were problems with your submission:</h4>
                            <ul class="list-disc list-inside text-xs space-y-1 text-rose-300 mt-1.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" @click="show = false" class="text-rose-400 hover:text-rose-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                Laravel 13 &bull; User Management System &bull; Final Exam Project
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <span>PHP 8.5</span>
                <span>&bull;</span>
                <span>SQLite</span>
                <span>&bull;</span>
                <span>Eloquent Relationships</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
