<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // Groups
            'groups.view', 'groups.create', 'groups.edit', 'groups.delete',
            'groups.manage-students',
            // Lessons
            'lessons.view', 'lessons.create', 'lessons.edit', 'lessons.delete',
            // Assignments
            'assignments.view', 'assignments.create', 'assignments.grade',
            'assignments.delete',
            // Students
            'students.view', 'students.create', 'students.edit', 'students.delete',
            // Teachers
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            // Attendance
            'attendance.view', 'attendance.mark',
            // Reports
            'reports.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Roles with permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $schoolAdmin = Role::firstOrCreate(['name' => 'school_admin', 'guard_name' => 'web']);
        $schoolAdmin->syncPermissions($permissions);

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacher->syncPermissions([
            'groups.view', 'groups.manage-students',
            'lessons.view', 'lessons.create', 'lessons.edit', 'lessons.delete',
            'assignments.view', 'assignments.create', 'assignments.grade',
            'students.view',
            'attendance.view', 'attendance.mark',
            'reports.view',
        ]);

        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $student->syncPermissions([
            'assignments.view',
            'attendance.view',
        ]);
    }
}
