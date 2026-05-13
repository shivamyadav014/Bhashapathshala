@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Create an account</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirm password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="role">I am joining as</label>
                        <select name="role" id="role" class="form-select">
                            <option value="student" @selected(old('role', 'student') === 'student')>Learner</option>
                            <option value="instructor" @selected(old('role') === 'instructor')>Instructor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="language_level">Language Level</label>
                        <select name="language_level" id="language_level" class="form-select">
                            <option value="beginner" @selected(old('language_level', 'beginner') === 'beginner')>Beginner</option>
                            <option value="intermediate" @selected(old('language_level') === 'intermediate')>Intermediate</option>
                            <option value="advanced" @selected(old('language_level') === 'advanced')>Advanced</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="goals">Learning Goals <span class="text-muted small">(optional)</span></label>
                        <input type="text" name="goals" id="goals" value="{{ old('goals') }}" class="form-control" placeholder="e.g. Speak fluently, pass an exam">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="mt-3 mb-0 text-center small text-muted">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
