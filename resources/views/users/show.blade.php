@extends('layouts.app')

@section('title', 'User Profile: ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    deleteModalOpen: false,
    confirmDelete() {
        this.deleteModalOpen = true;
    }
}">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('users.index') }}" class="hover:text-white transition">Users</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-indigo-400">{{ $user->name }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.edit', $user) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-medium shadow-md shadow-indigo-600/30 transition">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs sm:text-sm font-medium transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- User Profile Header Card -->
    <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl p-6 sm:p-8 backdrop-blur-sm relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-600/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 relative z-10 text-center sm:text-left">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center font-bold text-3xl shadow-xl shadow-indigo-600/30 shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <div class="flex-grow space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    <div>
                        @if($user->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Active Account
                            </span>
                        @elseif($user->status === 'inactive')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/30">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                Inactive
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                Suspended
                            </span>
                        @endif
                    </div>
                </div>

                <p class="text-sm text-slate-400 flex items-center justify-center sm:justify-start gap-2">
                    <i class="fa-regular fa-envelope text-indigo-400"></i>
                    <span>{{ $user->email }}</span>
                </p>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-2">
                    @if($user->department)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                            <i class="fa-solid fa-building text-[11px]"></i>
                            {{ $user->department->name }} ({{ $user->department->code }})
                        </span>
                    @endif

                    @if($user->role)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20">
                            <i class="fa-solid fa-shield-halved text-[11px]"></i>
                            {{ $user->role->name }}
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-slate-700/60 text-slate-300">
                        <i class="fa-regular fa-calendar text-[11px]"></i>
                        Joined {{ $user->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Relationships Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Department Details (Eloquent Relationship) -->
        <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl p-6 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-building text-amber-400"></i>
                    Department Relationship
                </h3>
                <span class="text-[10px] font-mono text-slate-400">belongsTo(Department)</span>
            </div>

            @if($user->department)
                <div class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Department Name</div>
                        <div class="font-semibold text-white mt-0.5">{{ $user->department->name }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Code</div>
                        <div class="font-mono text-indigo-300 mt-0.5">{{ $user->department->code }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Description</div>
                        <div class="text-slate-300 mt-0.5">{{ $user->department->description ?? 'No description available.' }}</div>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-500 italic py-4">No department assigned to this user.</p>
            @endif
        </div>

        <!-- Role Details (Eloquent Relationship) -->
        <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl p-6 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-violet-400"></i>
                    Role Assignment
                </h3>
                <span class="text-[10px] font-mono text-slate-400">belongsTo(Role)</span>
            </div>

            @if($user->role)
                <div class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Role Name</div>
                        <div class="font-semibold text-white mt-0.5">{{ $user->role->name }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Role Slug</div>
                        <div class="font-mono text-violet-300 mt-0.5">{{ $user->role->slug }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-400 font-medium">Description</div>
                        <div class="text-slate-300 mt-0.5">{{ $user->role->description ?? 'Standard role permissions.' }}</div>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-500 italic py-4">No role assigned to this user.</p>
            @endif
        </div>

    </div>

    <!-- Personal Profile (hasOne UserProfile Relationship) -->
    <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl p-6 sm:p-8 backdrop-blur-sm space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-700">
            <h3 class="text-sm font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-address-card text-emerald-400"></i>
                Personal Profile Information
            </h3>
            <span class="text-[10px] font-mono text-slate-400">hasOne(UserProfile)</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs sm:text-sm">
            <div>
                <div class="text-[11px] text-slate-400 font-medium">Phone Number</div>
                <div class="font-semibold text-white mt-0.5">
                    {{ $user->profile?->phone ?? 'Not provided' }}
                </div>
            </div>

            <div>
                <div class="text-[11px] text-slate-400 font-medium">City / Location</div>
                <div class="font-semibold text-white mt-0.5">
                    {{ $user->profile?->city ?? 'Not specified' }}
                </div>
            </div>

            <div>
                <div class="text-[11px] text-slate-400 font-medium">Date of Birth</div>
                <div class="font-semibold text-white mt-0.5">
                    {{ $user->profile?->birth_date ? $user->profile->birth_date->format('F d, Y') : 'Not specified' }}
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-2">
            <div>
                <div class="text-[11px] text-slate-400 font-medium">Street Address</div>
                <div class="text-slate-300 mt-0.5">
                    {{ $user->profile?->address ?? 'No physical address recorded.' }}
                </div>
            </div>

            <div>
                <div class="text-[11px] text-slate-400 font-medium">Biography / Notes</div>
                <div class="text-slate-300 mt-0.5 bg-slate-900/50 rounded-xl p-3 border border-slate-700/50">
                    {{ $user->profile?->bio ?? 'No biography added yet.' }}
                </div>
            </div>
        </div>

        <!-- Danger Zone Footer (Delete) -->
        <div class="pt-6 border-t border-slate-700/80 flex items-center justify-between">
            <div class="text-xs text-slate-400">
                User Record ID: <span class="font-mono text-slate-300">#{{ $user->id }}</span>
            </div>
            <button type="button"
                    @click="confirmDelete()"
                    class="px-4 py-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/30 text-xs sm:text-sm font-medium transition cursor-pointer flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i>
                <span>Delete Account</span>
            </button>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Alpine.js) -->
    <div x-show="deleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModalOpen = false"
             class="bg-slate-800 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-left">
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center mb-4">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Delete User Profile</h3>
            <p class="text-xs sm:text-sm text-slate-400 mt-2">
                Are you sure you want to permanently delete <strong class="text-rose-300">{{ $user->name }}</strong>?
            </p>

            <form action="{{ route('users.destroy', $user) }}" method="POST" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button"
                        @click="deleteModalOpen = false"
                        class="px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white text-xs sm:text-sm font-medium transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs sm:text-sm font-medium shadow-lg shadow-rose-600/30 transition cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Confirm Delete</span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
