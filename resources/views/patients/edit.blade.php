<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700 p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-center text-gray-100">Edit Patient</h1>
                    <p class="mt-2 text-center text-gray-400">Update Patient information</p>
                </div>

                <form action="{{ route('patients.update', $patient->user->id) }}" method="post" class="space-y-6">
                    @method('PUT')
                    @csrf

                    <x-message></x-message>

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-300">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ $patient->user->name }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200 placeholder-gray-400">
                        @error('name')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-gray-300">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ $patient->user->email }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200 placeholder-gray-400">
                        @error('email')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-semibold text-gray-300">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ $patient->phone }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200 placeholder-gray-400">
                        @error('phone')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="age" class="text-sm font-semibold text-gray-300">Age</label>
                        <input type="number" name="age" id="age" value="{{ $patient->age }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200 placeholder-gray-400">
                        @error('age')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="address" class="text-sm font-semibold text-gray-300">Address</label>
                        <input type="text" name="address" id="address" value="{{ $patient->address }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200 placeholder-gray-400">
                        @error('address')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="gender" class="text-sm font-semibold text-gray-300">Gender</label>
                        <select id="gender" name="gender"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition duration-200">
                            @foreach ($genders as $key => $value)
                                <option value="{{ $key }}" {{ $patient->gender === $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transition duration-200 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                            Update Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>