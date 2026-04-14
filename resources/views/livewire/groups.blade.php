<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Groups</h1>
        <button wire:click="$set('showForm', true)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ New Group</button>
    </div>

    @if ($showForm)
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">{{ $editingId ? 'Edit Group' : 'New Group' }}</h2>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" class="mt-1 block w-full rounded-lg border-gray-300" rows="2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teacher</label>
                    <select wire:model="teacher_id" class="mt-1 block w-full rounded-lg border-gray-300">
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save</button>
                    <button type="button" wire:click="$set('showForm', false)" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    @endif

    @if ($managingStudentsGroupId)
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Manage Students</h2>
            <div class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                @foreach ($students as $student)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="selectedStudents" value="{{ $student->id }}" class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm text-gray-700">{{ $student->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex gap-2">
                <button wire:click="syncStudents" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save</button>
                <button wire:click="$set('managingStudentsGroupId', null)" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($groups as $group)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">{{ $group->name }}</h3>
                    <div class="flex gap-1">
                        <button wire:click="edit({{ $group->id }})" class="text-xs text-indigo-600 hover:text-indigo-800 px-2 py-1 rounded">Edit</button>
                        <button wire:click="delete({{ $group->id }})" wire:confirm="Delete this group?" class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded">Del</button>
                    </div>
                </div>
                @if ($group->description)
                    <p class="text-xs text-gray-500 mb-2">{{ $group->description }}</p>
                @endif
                <p class="text-xs text-gray-500">Teacher: {{ $group->teacher->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-400 mb-3">{{ $group->students->count() }} students</p>
                <button wire:click="manageStudents({{ $group->id }})" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">Manage Students</button>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-400">
                <p class="text-lg">No groups yet.</p>
                <p class="text-sm">Create your first group to get started.</p>
            </div>
        @endforelse
    </div>
</div>
