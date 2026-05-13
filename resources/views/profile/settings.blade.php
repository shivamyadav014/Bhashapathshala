@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<h1 class="h3 mb-4">Account settings</h1>
<div class="card shadow-sm" style="max-width: 520px;">
    <div class="card-body">
        <h2 class="h6 text-muted mb-3">Change password</h2>
        <form method="POST" action="{{ route('settings') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="current_password">Current password</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">New password</label>
                <input type="password" name="password" id="password" class="form-control" minlength="8">
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirm new password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update password</button>
        </form>
    </div>
</div>
@endsection
