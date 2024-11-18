<x-user-layout>
    <x-slot name="title">Create User</x-slot>
    <x-slot name="main">
        <div class="container d-flex justify-content-center ">
            <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
                <h1 class="text-center h4 mb-4">Create User</h1>
                <form action="{{route('users.store')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter your name">
                        @error('name')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email">
                        @error('email')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password">
                        @error('password')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Confirm your password">
                        @error('password_confirmation')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>
</x-user-layout>