<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication') - User Management System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen text-slate-100 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md sm:max-w-lg">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white shadow-lg shadow-indigo-500/30 mb-4">
                <i class="fa-solid fa-users-gear text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Laravel 13 UMS</h1>
            <p class="text-sm text-slate-400 mt-1">Enterprise User Management System</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-400 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl bg-rose-500/10 border border-rose-500/20 p-4 text-sm text-rose-400 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-500/10 border border-rose-500/20 p-4 text-sm text-rose-400">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-2xl shadow-2xl p-6 sm:p-8">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-500 mt-8">
            &copy; {{ date('Y') }} User Management System &bull; Final Exam Project &bull; Laravel 13
        </div>
    </div>
</body>
</html>
