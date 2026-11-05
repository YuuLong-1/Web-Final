@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-white">Sign In to Your Account</h2>
    <p class="text-xs text-slate-400 mt-1">Enter your credentials to access the user management console</p>
</div>

<!-- Quick Demo Account helper -->
<div class="mb-6 p-3.5 rounded-xl bg-indigo-950/60 border border-indigo-500/30 text-xs text-indigo-300">
    <div class="flex items-center justify-between mb-1.5">
        <span class="font-medium text-indigo-200"><i class="fa-solid fa-key mr-1"></i> Demo Credentials:</span>
        <button type="button" onclick="fillAdminCredentials()" class="text-[11px] underline text-indigo-400 hover:text-indigo-200 font-medium">
            Auto-fill Admin
        </button>
    </div>
    <div class="font-mono text-slate-300">
        <div>Email: <span class="text-white">admin@example.com</span></div>
        <div>Password: <span class="text-white">password</span></div>
    </div>
</div>

<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">Email Address</label>
        <div class="relative rounded-lg shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="fa-regular fa-envelope text-sm"></i>
            </div>
            <input type="email" name="email" id="email" required
                value="{{ old('email') }}"
                placeholder="name@example.com"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-3 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-xs font-medium text-slate-300">Password</label>
        </div>
        <div class="relative rounded-lg shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="fa-solid fa-lock text-sm"></i>
            </div>
            <input type="password" name="password" id="password" required
                placeholder="••••••••"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-10 py-2.5 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-200">
                <i id="passwordToggleIcon" class="fa-regular fa-eye text-sm"></i>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between text-xs">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500">
            <span class="text-slate-300">Remember me</span>
        </label>
    </div>

    <button type="submit"
        class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-sm shadow-lg shadow-indigo-600/30 transition duration-150 cursor-pointer">
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        <span>Sign In</span>
    </button>
</form>

<div class="mt-6 pt-5 border-t border-slate-700/60 text-center text-xs text-slate-400">
    Don't have an account?
    <a href="{{ route('register') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 underline ml-1">
        Create an account
    </a>
</div>

<script>
    function fillAdminCredentials() {
        document.getElementById('email').value = 'admin@example.com';
        document.getElementById('password').value = 'password';
    }

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
