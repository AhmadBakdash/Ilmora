<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Students</h1>
        <button wire:click="$set('showForm', true)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Add Student</button>
    </div>

    @if ($showForm)
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">{{ $editingId ? 'Edit Student' : 'Add Student' }}</h2>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                    <input wire:model="password" type="password" class="mt-1 block w-full rounded-lg border-gray-300">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save</button>
                    <button type="button" wire:click="$set('showForm', false)" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $student->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $student->email }}</td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $student->id }})" class="text-xs text-indigo-600 hover:text-indigo-800 mr-2">Edit</button>
                            <button wire:click="delete({{ $student->id }})" wire:confirm="Delete this student?" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">No students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
