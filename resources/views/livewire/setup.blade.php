<div class="max-w-md mx-auto mt-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-indigo-600">Ilmora</h1>
        <p class="text-gray-500 mt-2">School Management Setup</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Create Your School</h2>
        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">School Name</label>
                <input wire:model="school_name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="My School">
                @error('school_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Admin Name</label>
                <input wire:model="admin_name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('admin_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Admin Email</label>
                <input wire:model="admin_email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('admin_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input wire:model="admin_password" type="password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('admin_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input wire:model="admin_password_confirmation" type="password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 font-medium">
                Create School & Admin Account
            </button>
        </form>
    </div>
</div>
