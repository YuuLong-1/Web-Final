<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $authUser;
    private Department $deptIt;
    private Department $deptHr;
    private Role $roleAdmin;
    private Role $roleStaff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deptIt = Department::create(['name' => 'IT Department', 'code' => 'IT']);
        $this->deptHr = Department::create(['name' => 'HR Department', 'code' => 'HR']);

        $this->roleAdmin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->roleStaff = Role::create(['name' => 'Staff', 'slug' => 'staff']);

        $this->authUser = User::create([
            'name' => 'Logged Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'department_id' => $this->deptIt->id,
            'role_id' => $this->roleAdmin->id,
            'status' => 'active',
        ]);
    }

    public function test_guests_cannot_access_user_management(): void
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_users_index(): void
    {
        $response = $this->actingAs($this->authUser)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users Directory');
        $response->assertSee('Logged Admin');
    }

    public function test_user_can_be_created_with_profile_and_relations(): void
    {
        $userData = [
            'name' => 'Alice Cooper',
            'email' => 'alice@test.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'department_id' => $this->deptHr->id,
            'role_id' => $this->roleStaff->id,
            'status' => 'active',
            'phone' => '+1 (555) 999-1111',
            'city' => 'Chicago',
            'address' => '500 Michigan Ave',
            'bio' => 'Talent recruiter and coordinator.',
            'birth_date' => '1992-08-20',
        ];

        $response = $this->actingAs($this->authUser)->post('/users', $userData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Alice Cooper',
            'email' => 'alice@test.com',
            'department_id' => $this->deptHr->id,
            'role_id' => $this->roleStaff->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'phone' => '+1 (555) 999-1111',
            'city' => 'Chicago',
            'address' => '500 Michigan Ave',
            'bio' => 'Talent recruiter and coordinator.',
        ]);
    }

    public function test_user_details_can_be_viewed(): void
    {
        $targetUser = User::create([
            'name' => 'Bob Marley',
            'email' => 'bob@test.com',
            'password' => Hash::make('password'),
            'department_id' => $this->deptIt->id,
            'role_id' => $this->roleStaff->id,
            'status' => 'active',
        ]);

        UserProfile::create([
            'user_id' => $targetUser->id,
            'phone' => '+1 555 444 3333',
            'city' => 'Kingston',
        ]);

        $response = $this->actingAs($this->authUser)->get(route('users.show', $targetUser));

        $response->assertStatus(200);
        $response->assertSee('Bob Marley');
        $response->assertSee('IT Department');
        $response->assertSee('+1 555 444 3333');
    }

    public function test_user_can_be_updated(): void
    {
        $targetUser = User::create([
            'name' => 'Charlie Day',
            'email' => 'charlie@test.com',
            'password' => Hash::make('password'),
            'department_id' => $this->deptIt->id,
            'role_id' => $this->roleStaff->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->authUser)->put(route('users.update', $targetUser), [
            'name' => 'Charlie Kelly',
            'email' => 'charlie.kelly@test.com',
            'department_id' => $this->deptHr->id,
            'role_id' => $this->roleAdmin->id,
            'status' => 'inactive',
            'phone' => '+1 555 000 1111',
            'city' => 'Philadelphia',
            'bio' => 'Updated bio information.',
        ]);

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Charlie Kelly',
            'email' => 'charlie.kelly@test.com',
            'department_id' => $this->deptHr->id,
            'status' => 'inactive',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $targetUser->id,
            'city' => 'Philadelphia',
            'bio' => 'Updated bio information.',
        ]);
    }

    public function test_user_can_be_deleted(): void
    {
        $targetUser = User::create([
            'name' => 'To Be Deleted',
            'email' => 'delete.me@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->authUser)->delete(route('users.destroy', $targetUser));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_user_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->authUser)->delete(route('users.destroy', $this->authUser));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->authUser->id]);
    }

    public function test_search_users_by_name(): void
    {
        User::create([
            'name' => 'UniqueSearchableName',
            'email' => 'unique@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'DifferentPerson',
            'email' => 'different@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->authUser)->get('/users?search=UniqueSearchableName');

        $response->assertStatus(200);
        $response->assertSee('UniqueSearchableName');
        $response->assertDontSee('DifferentPerson');
    }

    public function test_filter_users_by_department(): void
    {
        $userIt = User::create([
            'name' => 'IT Worker',
            'email' => 'it.worker@test.com',
            'password' => Hash::make('password'),
            'department_id' => $this->deptIt->id,
        ]);

        $userHr = User::create([
            'name' => 'HR Worker',
            'email' => 'hr.worker@test.com',
            'password' => Hash::make('password'),
            'department_id' => $this->deptHr->id,
        ]);

        $response = $this->actingAs($this->authUser)->get('/users?department_id=' . $this->deptHr->id);

        $response->assertStatus(200);
        $response->assertSee('HR Worker');
        $response->assertDontSee('it.worker@test.com');
    }

    public function test_filter_users_by_role(): void
    {
        $adminUser = User::create([
            'name' => 'Special Admin',
            'email' => 'spec.admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => $this->roleAdmin->id,
        ]);

        $staffUser = User::create([
            'name' => 'Special Staff',
            'email' => 'spec.staff@test.com',
            'password' => Hash::make('password'),
            'role_id' => $this->roleStaff->id,
        ]);

        $response = $this->actingAs($this->authUser)->get('/users?role_id=' . $this->roleStaff->id);

        $response->assertStatus(200);
        $response->assertSee('Special Staff');
        $response->assertDontSee('spec.admin@test.com');
    }

    public function test_filter_users_by_status(): void
    {
        User::create([
            'name' => 'Active Worker',
            'email' => 'active.worker@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Suspended Guy',
            'email' => 'suspended@test.com',
            'password' => Hash::make('password'),
            'status' => 'suspended',
        ]);

        $response = $this->actingAs($this->authUser)->get('/users?status=suspended');

        $response->assertStatus(200);
        $response->assertSee('Suspended Guy');
        $response->assertDontSee('active.worker@test.com');
    }
}
