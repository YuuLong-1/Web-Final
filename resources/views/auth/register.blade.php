@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-white">Create a New Account</h2>
    <p class="text-xs text-slate-400 mt-1">Fill in the fields below to join the User Management System</p>
</div>

<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block text-xs font-medium text-slate-300 mb-1.5">Full Name *</label>
        <div class="relative rounded-lg shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="fa-regular fa-user text-sm"></i>
            </div>
            <input type="text" name="name" id="name" required
                value="{{ old('name') }}"
                placeholder="John Doe"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>
    </div>

    <div>
        <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">Email Address *</label>
        <div class="relative rounded-lg shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="fa-regular fa-envelope text-sm"></i>
            </div>
            <input type="email" name="email" id="email" required
                value="{{ old('email') }}"
                placeholder="john@example.com"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="department_id" class="block text-xs font-medium text-slate-300 mb-1.5">Department</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <select name="department_id" id="department_id"
                    class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dep)
                        <option value="{{ $dep->id }}" {{ old('department_id') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->name }} ({{ $dep->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="role_id" class="block text-xs font-medium text-slate-300 mb-1.5">Role</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <select name="role_id" id="role_id"
                    class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 pl-10 pr-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="phone" class="block text-xs font-medium text-slate-300 mb-1.5">Phone Number</label>
            <input type="text" name="phone" id="phone"
                value="{{ old('phone') }}"
                placeholder="+1 234 567 890"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>

        <div>
            <label for="city" class="block text-xs font-medium text-slate-300 mb-1.5">City</label>
            <input type="text" name="city" id="city"
                value="{{ old('city') }}"
                placeholder="e.g. New York"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">Password *</label>
            <input type="password" name="password" id="password" required
                placeholder="Min 8 characters"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1.5">Confirm Password *</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                placeholder="Repeat password"
                class="block w-full rounded-lg bg-slate-900/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
        </div>
    </div>

    <button type="submit"
        class="w-full mt-2 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-sm shadow-lg shadow-indigo-600/30 transition duration-150 cursor-pointer">
        <i class="fa-solid fa-user-plus"></i>
        <span>Create Account & Sign In</span>
    </button>
</form>

<div class="mt-6 pt-5 border-t border-slate-700/60 text-center text-xs text-slate-400">
    Already registered?
    <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 underline ml-1">
        Sign in here
    </a>
</div>
@endsection
