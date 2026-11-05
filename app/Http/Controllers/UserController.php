<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and filtering capabilities.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $departmentId = $request->input('department_id');
        $roleId = $request->input('role_id');
        $status = $request->input('status');
        $sortBy = in_array($request->input('sort_by'), ['name', 'email', 'status', 'created_at'], true)
            ? $request->input('sort_by')
            : 'created_at';
        $sortOrder = $request->input('sort_order') === 'asc' ? 'asc' : 'desc';

        $query = User::with(['department', 'role', 'profile']);

        // Search by name or email
        if (!empty($search)) {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by Department
        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        // Filter by Role
        if (!empty($roleId)) {
            $query->where('role_id', $roleId);
        }

        // Filter by Status
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $users = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();

        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'departments_count' => Department::count(),
        ];

        return view('users.index', compact('users', 'departments', 'roles', 'stats', 'search', 'departmentId', 'roleId', 'status', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user and profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'department_id' => $validated['department_id'] ?? null,
                'role_id' => $validated['role_id'] ?? null,
                'status' => $validated['status'],
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'address' => $validated['address'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
            ]);

            return $user;
        });

        return redirect()->route('users.index')
            ->with('success', 'User ' . $validated['name'] . ' was created successfully!');
    }

    /**
     * Display the specified user profile details.
     */
    public function show(User $user): View
    {
        $user->load(['department', 'role', 'profile']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->load(['profile']);
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user and profile in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($user, $validated): void {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'department_id' => $validated['department_id'] ?? null,
                'role_id' => $validated['role_id'] ?? null,
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $validated['phone'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'birth_date' => $validated['birth_date'] ?? null,
                ]
            );
        });

        return redirect()->route('users.index')
            ->with('success', 'User ' . $user->name . ' was updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own logged-in account!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User ' . $userName . ' was deleted successfully.');
    }
}
