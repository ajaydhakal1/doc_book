<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Permissions') }}
            </h2>
            <a href="{{route('permissions.create')}}">
                <button class="py-2 px-5 bg-slate-600 text-white rounded-md hover:bg-slate-500">Create</button>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <table class="w-full bg-gray-800 text-white">
                <thead class="bg-gray-700">
                    <tr class="border-b border-gray-600">
                        <th class="px-6 py-3 text-left" width="60">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left" width="180">Created</th>
                        <th class="px-6 py-3 text-center" colspan="2" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800">
                    @if ($permissions->isNotEmpty())
                        @foreach ($permissions as $permission)
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-3 text-left">
                                    {{$permission->id}}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{$permission->name}}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{Carbon\Carbon::parse($permission->created_at)->format('d M Y')}}
                                </td>
                                <div class="flex gap-1">
                                    <td class="py-3 text-center">
                                        <a href="{{route('permissions.edit', $permission->id)}}">
                                            <button
                                                class="py-2 px-5 bg-slate-600 text-white rounded-md hover:bg-slate-500">Edit</button>
                                        </a>
                                    </td>
                                    <td class="py-3 text-center">
                                        <form action="{{route('permissions.destroy', $permission->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="py-2 px-5 bg-red-600 text-white rounded-md hover:bg-red-500"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
                                </div>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-3">No permissions found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="my-3">
                {{$permissions->links()}}
            </div>
        </div>
    </div>
</x-app-layout>