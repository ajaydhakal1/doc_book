<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-800 to-gray-900 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-center text-gray-900">Create New User</h1>
                    <p class="mt-2 text-center text-gray-600">Add a new user to the system</p>
                </div>

                <form action="{{ route('users.store') }}" method="post" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200"
                            placeholder="John Doe">
                        @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200"
                            placeholder="john@example.com">
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200"
                            placeholder="••••••••">
                        @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="space-y-2">
                        <label for="role" class="text-sm font-semibold text-gray-700">User Role</label>
                        <select id="role" name="role"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200">
                            <option value="" disabled selected>Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transition duration-200 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>