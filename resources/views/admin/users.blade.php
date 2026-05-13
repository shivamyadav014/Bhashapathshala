@extends('layouts.app')

@section('title', 'Admin - Users')

@section('content')
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Manage</span>
        <h1 class="h3 mb-1">Users</h1>
        <p class="text-muted mb-0">Search accounts, review activity, and update roles.</p>
    </div>
</div>

<form method="GET" class="card mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name or email">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    @foreach(['student', 'instructor', 'admin'] as $role)
                        <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filter</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Activity</th>
                    <th>Joined</th>
                    <th>Change role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $user->role }}</span></td>
                        <td class="small text-muted">
                            {{ $user->enrollments_count }} enrollments · {{ $user->quiz_results_count }} quiz attempts
                        </td>
                        <td class="small text-muted">{{ $user->created_at?->format('M d, Y') }}</td>
                        <td style="min-width: 240px;">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-select form-select-sm">
                                    @foreach(['student', 'instructor', 'admin'] as $role)
                                        <option value="{{ $role }}" @selected($user->role === $role)>{{ $role }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center py-4">No users match your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
