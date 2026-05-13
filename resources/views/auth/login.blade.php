@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Sign in</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 mb-0 text-center small text-muted">
                    No account? <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
