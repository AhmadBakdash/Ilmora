<div class="max-w-md mx-auto mt-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-indigo-600">Ilmora</h1>
        <p class="text-gray-500 mt-2">Sign in to your account</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input wire:model="password" type="password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 font-medium">
                Sign In
            </button>
        </form>
    </div>
</div>
