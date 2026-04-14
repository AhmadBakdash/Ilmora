<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::create(['name' => 'Demo School', 'slug' => 'demo-school']);

        $teacher = User::create([
            'name' => 'Jane Teacher',
            'email' => 'teacher@demo.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'role' => 'school_admin',
        ]);

        $students = [];
        foreach (['Alice', 'Bob', 'Carol', 'David', 'Eva'] as $studentName) {
            $students[] = User::create([
                'name' => $studentName,
                'email' => strtolower($studentName) . '@demo.com',
                'password' => Hash::make('password'),
                'school_id' => $school->id,
                'role' => 'student',
            ]);
        }

        $group = Group::create([
            'school_id' => $school->id,
            'name' => 'Class 10A',
            'description' => 'Mathematics & Science',
            'teacher_id' => $teacher->id,
        ]);

        $group->students()->attach(array_map(fn($s) => $s->id, $students));

        $lessons = [
            ['title' => 'Mathematics', 'day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '09:30', 'room' => '101'],
            ['title' => 'Physics', 'day_of_week' => 2, 'start_time' => '10:00', 'end_time' => '11:30', 'room' => '102'],
            ['title' => 'Chemistry', 'day_of_week' => 3, 'start_time' => '08:00', 'end_time' => '09:30', 'room' => '103'],
            ['title' => 'Biology', 'day_of_week' => 4, 'start_time' => '13:00', 'end_time' => '14:30', 'room' => '104'],
            ['title' => 'English', 'day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '10:30', 'room' => '105'],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create(array_merge($lessonData, [
                'school_id' => $school->id,
                'group_id' => $group->id,
                'teacher_id' => $teacher->id,
            ]));
        }

        $mathLesson = Lesson::where('title', 'Mathematics')->first();
        $assignment = Assignment::create([
            'lesson_id' => $mathLesson->id,
            'title' => 'Chapter 5 Exercises',
            'description' => 'Complete exercises 5.1 to 5.10',
            'due_date' => Carbon::now()->addWeek(),
        ]);

        foreach ($students as $student) {
            $assignment->students()->attach($student->id, ['status' => 'pending']);
        }
    }
}
