<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('users.index'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create a New Account');
    }

    public function test_users_can_register_and_profile_is_created(): void
    {
        $department = Department::create(['name' => 'Tech', 'code' => 'TECH']);
        $role = Role::create(['name' => 'Developer', 'slug' => 'developer']);

        $response = $this->post('/register', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department_id' => $department->id,
            'role_id' => $role->id,
            'phone' => '+1234567890',
            'city' => 'New York',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'department_id' => $department->id,
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'phone' => '+1234567890',
            'city' => 'New York',
        ]);
    }

    public function test_users_can_logout(): void
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
