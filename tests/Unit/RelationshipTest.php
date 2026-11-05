<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_belongs_to_department(): void
    {
        $department = Department::create([
            'name' => 'Engineering',
            'code' => 'ENG',
            'description' => 'Software and Hardware engineering',
        ]);

        $user = User::create([
            'name' => 'Engineer Dave',
            'email' => 'dave@example.com',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
        ]);

        $this->assertInstanceOf(Department::class, $user->department);
        $this->assertSame('Engineering', $user->department->name);
        $this->assertSame('ENG', $user->department->code);
    }

    public function test_department_has_many_users(): void
    {
        $department = Department::create([
            'name' => 'Marketing',
            'code' => 'MKT',
        ]);

        $user1 = User::create([
            'name' => 'Marketer 1',
            'email' => 'm1@example.com',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
        ]);

        $user2 = User::create([
            'name' => 'Marketer 2',
            'email' => 'm2@example.com',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
        ]);

        $this->assertCount(2, $department->users);
        $this->assertTrue($department->users->contains($user1));
        $this->assertTrue($department->users->contains($user2));
    }

    public function test_user_belongs_to_role(): void
    {
        $role = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'superadmin',
        ]);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin.user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
        ]);

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertSame('Super Administrator', $user->role->name);
        $this->assertSame('superadmin', $user->role->slug);
    }

    public function test_role_has_many_users(): void
    {
        $role = Role::create([
            'name' => 'Guest Contributor',
            'slug' => 'guest',
        ]);

        $user = User::create([
            'name' => 'Contributor',
            'email' => 'contributor@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
        ]);

        $this->assertCount(1, $role->users);
        $this->assertTrue($role->users->contains($user));
    }

    public function test_user_has_one_profile_and_profile_belongs_to_user(): void
    {
        $user = User::create([
            'name' => 'Profile Owner',
            'email' => 'profile.owner@example.com',
            'password' => Hash::make('password'),
        ]);

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'phone' => '+1 (555) 321-4321',
            'city' => 'Austin',
            'address' => '789 Congress Ave',
            'bio' => 'Passionate tech lead.',
        ]);

        $this->assertInstanceOf(UserProfile::class, $user->profile);
        $this->assertSame('+1 (555) 321-4321', $user->profile->phone);
        $this->assertSame('Austin', $user->profile->city);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertSame($user->id, $profile->user->id);
    }

    public function test_deleting_user_cascades_to_profile(): void
    {
        $user = User::create([
            'name' => 'Temporary User',
            'email' => 'temp@example.com',
            'password' => Hash::make('password'),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'phone' => '123456789',
        ]);

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('user_profiles', ['user_id' => $user->id]);
    }
}
