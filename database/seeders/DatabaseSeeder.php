<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $departmentsData = [
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Software development, cloud architecture, and IT operations.'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Talent acquisition, employee welfare, and personnel management.'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN', 'description' => 'Financial planning, audits, budget management, and accounting.'],
            ['name' => 'Marketing & Sales', 'code' => 'MKT', 'description' => 'Brand management, digital marketing, campaigns, and sales operations.'],
            ['name' => 'Operations & Logistics', 'code' => 'OPS', 'description' => 'Supply chain, business logistics, and facility management.'],
        ];

        $departments = [];
        foreach ($departmentsData as $dep) {
            $departments[$dep['code']] = Department::firstOrCreate(['code' => $dep['code']], $dep);
        }

        // 2. Seed Roles
        $rolesData = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full administrative access and user management rights.'],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Team management, reporting, and operational oversight.'],
            ['name' => 'Team Lead', 'slug' => 'lead', 'description' => 'Supervises technical workflows and department tasks.'],
            ['name' => 'Staff Specialist', 'slug' => 'staff', 'description' => 'Standard departmental specialist and staff duties.'],
            ['name' => 'Member', 'slug' => 'member', 'description' => 'Basic member profile and standard system access.'],
        ];

        $roles = [];
        foreach ($rolesData as $role) {
            $roles[$role['slug']] = Role::firstOrCreate(['slug' => $role['slug']], $role);
        }

        // 3. Seed Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator User',
                'password' => Hash::make('password'),
                'department_id' => $departments['IT']->id,
                'role_id' => $roles['admin']->id,
                'status' => 'active',
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'phone' => '+1 (555) 019-2834',
                'city' => 'San Francisco',
                'address' => '100 Silicon Blvd, Suite 400',
                'bio' => 'System administrator managing users, roles, and enterprise infrastructure.',
                'birth_date' => '1990-05-15',
            ]
        );

        // 4. Seed Diverse Sample Users
        $sampleUsers = [
            [
                'name' => 'Alex Johnson',
                'email' => 'alex.johnson@example.com',
                'department' => 'IT',
                'role' => 'lead',
                'status' => 'active',
                'phone' => '+1 (555) 123-4567',
                'city' => 'Seattle',
                'address' => '456 Pine Ave',
                'bio' => 'Senior Full-Stack Architect specialized in Laravel and cloud systems.',
            ],
            [
                'name' => 'Sarah Connor',
                'email' => 'sarah.connor@example.com',
                'department' => 'HR',
                'role' => 'manager',
                'status' => 'active',
                'phone' => '+1 (555) 234-5678',
                'city' => 'New York',
                'address' => '789 Broadway St',
                'bio' => 'Human Resources Lead fostering vibrant workplace culture and talent.',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'department' => 'FIN',
                'role' => 'manager',
                'status' => 'active',
                'phone' => '+1 (555) 345-6789',
                'city' => 'Chicago',
                'address' => '321 Michigan Ave',
                'bio' => 'Chief Financial Analyst overseeing budgeting and strategic audits.',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'department' => 'MKT',
                'role' => 'lead',
                'status' => 'active',
                'phone' => '+1 (555) 456-7890',
                'city' => 'Austin',
                'address' => '654 Congress Ave',
                'bio' => 'Brand strategy consultant and growth marketing director.',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@example.com',
                'department' => 'IT',
                'role' => 'staff',
                'status' => 'active',
                'phone' => '+1 (555) 567-8901',
                'city' => 'San Jose',
                'address' => '987 Tech Center Way',
                'bio' => 'DevOps engineer focused on containerization and CI/CD pipelines.',
            ],
            [
                'name' => 'Jessica Taylor',
                'email' => 'jessica.taylor@example.com',
                'department' => 'HR',
                'role' => 'staff',
                'status' => 'inactive',
                'phone' => '+1 (555) 678-9012',
                'city' => 'Boston',
                'address' => '12 Beacon St',
                'bio' => 'Recruitment officer and benefits coordinator on temporary leave.',
            ],
            [
                'name' => 'Robert Davis',
                'email' => 'robert.davis@example.com',
                'department' => 'OPS',
                'role' => 'manager',
                'status' => 'active',
                'phone' => '+1 (555) 789-0123',
                'city' => 'Denver',
                'address' => '555 Mountain View Rd',
                'bio' => 'Warehouse logistics director managing regional supply chain operations.',
            ],
            [
                'name' => 'Sophia Martinez',
                'email' => 'sophia.martinez@example.com',
                'department' => 'MKT',
                'role' => 'staff',
                'status' => 'active',
                'phone' => '+1 (555) 890-1234',
                'city' => 'Miami',
                'address' => '22 Ocean Drive',
                'bio' => 'Creative copywriter and social media content producer.',
            ],
            [
                'name' => 'William Brown',
                'email' => 'william.brown@example.com',
                'department' => 'FIN',
                'role' => 'staff',
                'status' => 'suspended',
                'phone' => '+1 (555) 901-2345',
                'city' => 'Dallas',
                'address' => '888 Main St',
                'bio' => 'Junior corporate tax compliance analyst.',
            ],
            [
                'name' => 'Olivia Wilson',
                'email' => 'olivia.wilson@example.com',
                'department' => 'OPS',
                'role' => 'staff',
                'status' => 'active',
                'phone' => '+1 (555) 012-3456',
                'city' => 'Phoenix',
                'address' => '444 Desert Sun Way',
                'bio' => 'Quality assurance inspector and process optimization specialist.',
            ],
            [
                'name' => 'Daniel Garcia',
                'email' => 'daniel.garcia@example.com',
                'department' => 'IT',
                'role' => 'member',
                'status' => 'active',
                'phone' => '+1 (555) 112-2334',
                'city' => 'San Diego',
                'address' => '77 Pacific Hwy',
                'bio' => 'Front-end intern enthusiast building responsive interfaces.',
            ],
            [
                'name' => 'Emma White',
                'email' => 'emma.white@example.com',
                'department' => 'HR',
                'role' => 'member',
                'status' => 'inactive',
                'phone' => '+1 (555) 223-3445',
                'city' => 'Portland',
                'address' => '330 Pearl District',
                'bio' => 'HR intern assisting onboarding workflows.',
            ],
        ];

        foreach ($sampleUsers as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => Hash::make('password'),
                    'department_id' => $departments[$item['department']]->id ?? null,
                    'role_id' => $roles[$item['role']]->id ?? null,
                    'status' => $item['status'],
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $item['phone'],
                    'city' => $item['city'],
                    'address' => $item['address'],
                    'bio' => $item['bio'],
                ]
            );
        }
    }
}
