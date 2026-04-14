<div x-data="{ tab: 'attendance' }">
    <div class="flex border-b mb-4">
        <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'" class="px-4 py-2 text-sm font-medium">Attendance</button>
        <button @click="tab = 'assignments'" :class="tab === 'assignments' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'" class="px-4 py-2 text-sm font-medium">Assignments</button>
    </div>

    <div x-show="tab === 'attendance'">
        <div class="flex items-center gap-3 mb-4">
            <label class="text-sm font-medium text-gray-700">Date:</label>
            <input wire:model.live="attendanceDate" type="date" class="rounded-lg border-gray-300 text-sm">
        </div>

        @if (session()->has('attendance_success'))
            <div class="bg-green-50 text-green-700 px-3 py-2 rounded-lg text-sm mb-3">{{ session('attendance_success') }}</div>
        @endif

        @if ($lesson && $lesson->group && $lesson->group->students->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach ($lesson->group->students as $student)
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-sm text-gray-700">{{ $student->name }}</span>
                        <select wire:model="attendanceStatuses.{{ $student->id }}" class="text-sm rounded-lg border-gray-300">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                        </select>
                    </div>
                @endforeach
            </div>
            <button wire:click="saveAttendance" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save Attendance</button>
        @else
            <p class="text-sm text-gray-400">No students in this group.</p>
        @endif
    </div>

    <div x-show="tab === 'assignments'">
        @if (session()->has('assignment_success'))
            <div class="bg-green-50 text-green-700 px-3 py-2 rounded-lg text-sm mb-3">{{ session('assignment_success') }}</div>
        @endif

        <div class="bg-gray-50 rounded-xl p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">New Assignment</h3>
            <div class="space-y-3">
                <input wire:model="assignmentTitle" type="text" placeholder="Title" class="w-full rounded-lg border-gray-300 text-sm">
                @error('assignmentTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <textarea wire:model="assignmentDescription" placeholder="Description (optional)" class="w-full rounded-lg border-gray-300 text-sm" rows="2"></textarea>
                <input wire:model="assignmentDueDate" type="date" class="rounded-lg border-gray-300 text-sm">
                <button wire:click="createAssignment" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Create Assignment</button>
            </div>
        </div>

        @if ($lesson && $lesson->assignments->count() > 0)
            <div class="space-y-3">
                @foreach ($lesson->assignments as $assignment)
                    <div class="border rounded-xl p-3">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-800">{{ $assignment->title }}</p>
                            @if ($assignment->due_date)
                                <span class="text-xs text-gray-400">Due: {{ $assignment->due_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                        @if ($assignment->description)
                            <p class="text-xs text-gray-500 mb-2">{{ $assignment->description }}</p>
                        @endif
                        <div class="text-xs text-gray-400">
                            {{ $assignment->students->where('pivot.status', 'done')->count() }} / {{ $assignment->students->count() }} completed
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400">No assignments yet.</p>
        @endif
    </div>
</div>
