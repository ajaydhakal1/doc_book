<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Permissions/Create') }}
            </h2>
            <a href="{{route('permissions.index')}}">
                <button class="py-2 px-5 bg-slate-600 text-white rounded-md hover:bg-slate-500">Back</button>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <form action="{{route('permissions.store')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black"
                                    id="name" name="name" placeholder="Enter permission name" value="{{old('name')}}">
                            </div>
                            @error('name')
                                <p class="text-red-500 font-medium">{{$message}}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="bg-slate-600 text-white rounded-md py-2 px-5 hover:bg-slate-500">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('name').focus();
        });
    </script>
</x-app-layout>