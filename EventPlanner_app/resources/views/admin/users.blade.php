@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
<div class="user-management-page">
    <div class="container mt-5">

        {{-- Return Button inside the container --}}
        <p>
            <a href="{{ route('events.index') }}" class="glow-link">Back to Events</a>
        </p>

        <h2 class="mb-4">User Management</h2>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Year Level</th>
                    <th>Number</th>
                    <th>Role</th>
                    <th>Change Role</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->user_id }}</td>
                        <td>{{ $u->user_student_id }}</td>
                        <td>{{ $u->user_name }}</td>
                        <td>{{ $u->user_email }}</td>
                        <td>{{ $u->user_year_lvl }}</td>
                        <td>{{ $u->user_number }}</td>
                        <td>
                            @if($u->roles == 2)
                                <span class="badge bg-success">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.users.updateRole', $u->user_id) }}" method="POST">
                                @csrf
                                <select name="roles" class="form-select form-select-sm d-inline w-auto">
                                    <option value="1" {{ $u->roles == 1 ? 'selected' : '' }}>User</option>
                                    <option value="2" {{ $u->roles == 2 ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.users.destroy', $u->user_id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection