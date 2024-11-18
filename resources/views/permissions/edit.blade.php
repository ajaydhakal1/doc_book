<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Permissions/Edit') }}
            </h2>
            <a href="{{route('permissions.index')}}">
                <button class="py-2 px-5 border-gray-400 bg-slate-300 rounded-md">Back</button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('permissions.update', $permission->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black" id="name"
                                    name="name" placeholder="Enter permission name" value="{{old('name', $permission->name)}}">
                            </div>
                            @error('name')
                                <p class="text-red-500 font-medium">{{$message}}</p>
                            @enderror
                        </div>

                        <button type="submit" class="bg-green-500 rounded-md py-2 px-5">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>