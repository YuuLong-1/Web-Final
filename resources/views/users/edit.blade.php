@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('users.index') }}" class="hover:text-white transition">Users</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('users.show', $user) }}" class="hover:text-white transition">{{ $user->name }}</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-indigo-400">Edit</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-indigo-400"></i>
                Edit User: {{ $user->name }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.show', $user) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs sm:text-sm font-medium transition">
                <i class="fa-regular fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs sm:text-sm font-medium transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl p-6 sm:p-8 backdrop-blur-sm space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1: Account Credentials -->
        <div>
            <div class="flex items-center gap-2 pb-3 border-b border-slate-700/80 mb-5">
                <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Account Credentials</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-medium text-slate-300 mb-1.5">Full Name *</label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $user->name) }}"
                           placeholder="e.g. Jane Doe"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">Email Address *</label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email', $user->email) }}"
                           placeholder="jane.doe@example.com"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">
                        New Password <span class="text-[11px] text-slate-400">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" id="password"
                           placeholder="••••••••"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Repeat new password"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>
            </div>
        </div>

        <!-- Section 2: Department & Role Assignment -->
        <div>
            <div class="flex items-center gap-2 pb-3 border-b border-slate-700/80 mb-5">
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Department & Role Assignment</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="department_id" class="block text-xs font-medium text-slate-300 mb-1.5">Department</label>
                    <select name="department_id" id="department_id"
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="">-- No Department --</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}" {{ old('department_id', $user->department_id) == $dep->id ? 'selected' : '' }}>
                                {{ $dep->name }} ({{ $dep->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="role_id" class="block text-xs font-medium text-slate-300 mb-1.5">Role</label>
                    <select name="role_id" id="role_id"
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="">-- No Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-medium text-slate-300 mb-1.5">Account Status *</label>
                    <select name="status" id="status" required
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 3: Profile Details -->
        <div>
            <div class="flex items-center gap-2 pb-3 border-b border-slate-700/80 mb-5">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-address-card"></i>
                </div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Personal Profile Details (One-to-One Relationship)</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="phone" class="block text-xs font-medium text-slate-300 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone', $user->profile?->phone) }}"
                           placeholder="+1 (555) 000-0000"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="city" class="block text-xs font-medium text-slate-300 mb-1.5">City</label>
                    <input type="text" name="city" id="city"
                           value="{{ old('city', $user->profile?->city) }}"
                           placeholder="San Francisco"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="birth_date" class="block text-xs font-medium text-slate-300 mb-1.5">Date of Birth</label>
                    <input type="date" name="birth_date" id="birth_date"
                           value="{{ old('birth_date', $user->profile?->birth_date?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="address" class="block text-xs font-medium text-slate-300 mb-1.5">Street Address</label>
                    <input type="text" name="address" id="address"
                           value="{{ old('address', $user->profile?->address) }}"
                           placeholder="123 Main Street, Suite 100"
                           class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                </div>

                <div>
                    <label for="bio" class="block text-xs font-medium text-slate-300 mb-1.5">Biography / Notes</label>
                    <textarea name="bio" id="bio" rows="3"
                              placeholder="Brief bio or role responsibility notes..."
                              class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">{{ old('bio', $user->profile?->bio) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Submission Footer -->
        <div class="pt-6 border-t border-slate-700/80 flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white text-xs sm:text-sm font-medium transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition duration-150 cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Save Changes</span>
            </button>
        </div>

    </form>
</div>
@endsection
