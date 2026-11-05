@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6" x-data="{
    deleteModalOpen: false,
    deleteAction: '',
    deleteUserName: '',
    confirmDelete(actionUrl, name) {
        this.deleteAction = actionUrl;
        this.deleteUserName = name;
        this.deleteModalOpen = true;
    }
}">

    <!-- Page Header & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-indigo-400"></i>
                Users Directory
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Manage system accounts, assign roles, departments, and profile records.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition duration-150 cursor-pointer">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add New User</span>
            </a>
        </div>
    </div>

    <!-- Metric Statistics Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Total Users</p>
                <p class="text-xl sm:text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
        </div>

        <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Active</p>
                <p class="text-xl sm:text-2xl font-bold text-emerald-400 mt-1">{{ $stats['active'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <i class="fa-solid fa-user-check text-lg"></i>
            </div>
        </div>

        <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Inactive</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-400 mt-1">{{ $stats['inactive'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-slate-500/10 text-slate-400 flex items-center justify-center">
                <i class="fa-solid fa-user-clock text-lg"></i>
            </div>
        </div>

        <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Suspended</p>
                <p class="text-xl sm:text-2xl font-bold text-rose-400 mt-1">{{ $stats['suspended'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center">
                <i class="fa-solid fa-user-slash text-lg"></i>
            </div>
        </div>

        <div class="col-span-2 lg:col-span-1 bg-slate-800/80 border border-slate-700/60 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Departments</p>
                <p class="text-xl sm:text-2xl font-bold text-amber-400 mt-1">{{ $stats['departments_count'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <i class="fa-solid fa-building text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-slate-800/70 border border-slate-700/70 rounded-2xl p-5 shadow-lg backdrop-blur-sm">
        <form action="{{ route('users.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">

                <!-- Search by Name or Email -->
                <div class="lg:col-span-4">
                    <label for="search" class="block text-xs font-medium text-slate-300 mb-1.5">
                        <i class="fa-solid fa-magnifying-glass mr-1 text-indigo-400"></i> Search Name / Email
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ $search }}"
                               placeholder="e.g. John Doe, admin..."
                               class="block w-full rounded-xl bg-slate-900 border border-slate-700 pl-3 pr-8 py-2 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        @if(!empty($search))
                            <a href="{{ route('users.index', request()->except('search')) }}"
                               title="Clear search"
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Filter Department -->
                <div class="lg:col-span-2">
                    <label for="department_id" class="block text-xs font-medium text-slate-300 mb-1.5">
                        <i class="fa-solid fa-building mr-1 text-indigo-400"></i> Department
                    </label>
                    <select name="department_id" id="department_id"
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="">All Departments</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}" {{ $departmentId == $dep->id ? 'selected' : '' }}>
                                {{ $dep->name }} ({{ $dep->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Role -->
                <div class="lg:col-span-2">
                    <label for="role_id" class="block text-xs font-medium text-slate-300 mb-1.5">
                        <i class="fa-solid fa-shield-halved mr-1 text-indigo-400"></i> Role
                    </label>
                    <select name="role_id" id="role_id"
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="lg:col-span-2">
                    <label for="status" class="block text-xs font-medium text-slate-300 mb-1.5">
                        <i class="fa-solid fa-circle-dot mr-1 text-indigo-400"></i> Status
                    </label>
                    <select name="status" id="status"
                            class="block w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2 text-xs sm:text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150">
                        <option value="">All Statuses</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <!-- Filter & Reset Buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs sm:text-sm shadow-md transition duration-150 cursor-pointer">
                        <i class="fa-solid fa-filter"></i>
                        <span>Apply</span>
                    </button>
                    <a href="{{ route('users.index') }}"
                       title="Reset all filters"
                       class="inline-flex items-center justify-center py-2 px-3 rounded-xl bg-slate-700/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-600 transition text-xs sm:text-sm">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    <!-- Active Filter Indicators -->
    @if(!empty($search) || !empty($departmentId) || !empty($roleId) || !empty($status))
        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400">
            <span class="font-medium text-slate-300">Active Filters:</span>
            @if(!empty($search))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-300 border border-indigo-500/30">
                    Search: "{{ $search }}"
                    <a href="{{ route('users.index', request()->except('search')) }}" class="hover:text-white"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                </span>
            @endif
            @if(!empty($departmentId))
                @php $depObj = $departments->firstWhere('id', $departmentId); @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/30">
                    Dept: {{ $depObj->name ?? $departmentId }}
                    <a href="{{ route('users.index', request()->except('department_id')) }}" class="hover:text-white"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                </span>
            @endif
            @if(!empty($roleId))
                @php $roleObj = $roles->firstWhere('id', $roleId); @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-violet-500/10 text-violet-300 border border-violet-500/30">
                    Role: {{ $roleObj->name ?? $roleId }}
                    <a href="{{ route('users.index', request()->except('role_id')) }}" class="hover:text-white"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                </span>
            @endif
            @if(!empty($status))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-700 text-slate-200">
                    Status: {{ ucfirst($status) }}
                    <a href="{{ route('users.index', request()->except('status')) }}" class="hover:text-white"><i class="fa-solid fa-xmark text-[10px]"></i></a>
                </span>
            @endif
            <a href="{{ route('users.index') }}" class="text-indigo-400 hover:underline ml-1">Clear all</a>
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl shadow-xl overflow-hidden backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/80 bg-slate-900/60 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6">User / Contact</th>
                        <th class="py-3.5 px-4">Department</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Joined</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60 text-xs sm:text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-750/50 transition-colors duration-150">
                            <!-- User info -->
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('users.show', $user) }}" class="font-semibold text-white hover:text-indigo-400 transition">
                                            {{ $user->name }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 flex items-center gap-2 mt-0.5">
                                            <span>{{ $user->email }}</span>
                                            @if($user->profile?->phone)
                                                <span>&bull;</span>
                                                <span><i class="fa-solid fa-phone text-[9px] mr-0.5"></i> {{ $user->profile->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Department -->
                            <td class="py-4 px-4">
                                @if($user->department)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                        <i class="fa-solid fa-building text-[10px]"></i>
                                        {{ $user->department->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500 italic">Unassigned</span>
                                @endif
                            </td>

                            <!-- Role -->
                            <td class="py-4 px-4">
                                @if($user->role)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-medium bg-violet-500/10 text-violet-300 border border-violet-500/20">
                                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500 italic">No Role</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Active
                                    </span>
                                @elseif($user->status === 'inactive')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        Suspended
                                    </span>
                                @endif
                            </td>

                            <!-- Joined Date -->
                            <td class="py-4 px-4 text-xs text-slate-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('users.show', $user) }}"
                                       title="View Profile Details"
                                       class="p-2 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-slate-300 hover:text-white transition">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}"
                                       title="Edit User"
                                       class="p-2 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 hover:text-indigo-300 transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button"
                                            title="Delete User"
                                            @click="confirmDelete('{{ route('users.destroy', $user) }}', '{{ addslashes($user->name) }}')"
                                            class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 transition cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-700/50 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-user-xmark text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-semibold text-white">No Users Found</h3>
                                    <p class="text-xs text-slate-400 mt-1">No user records matched your search or active filter criteria.</p>
                                    <a href="{{ route('users.index') }}" class="inline-block mt-4 text-xs font-semibold text-indigo-400 hover:underline">
                                        Clear search filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-700/80 bg-slate-900/40">
                {{ $users->links() }}
            </div>
        @endif
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
            <h3 class="text-lg font-bold text-white">Confirm User Deletion</h3>
            <p class="text-xs sm:text-sm text-slate-400 mt-2">
                Are you sure you want to delete user <strong class="text-rose-300" x-text="deleteUserName"></strong>? This will remove the account and their linked profile record permanently.
            </p>

            <form :action="deleteAction" method="POST" class="mt-6 flex items-center justify-end gap-3">
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
                    <span>Yes, Delete User</span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
