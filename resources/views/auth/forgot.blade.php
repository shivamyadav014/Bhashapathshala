@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Forgot Password</h1>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
