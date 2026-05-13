@extends('layouts.app')

@section('title', 'Instructor dashboard')

@section('content')
<h1 class="h3 mb-4">Instructor overview</h1>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Your courses</div>
            <div class="h4 mb-0">{{ $stats['courses'] }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Published</div>
            <div class="h4 mb-0">{{ $stats['published'] }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total enrollments</div>
            <div class="h4 mb-0">{{ $stats['learners'] }}</div>
        </div></div>
    </div>
</div>

<h2 class="h5 mb-3">Recent courses</h2>
<div class="list-group mb-4">
    @forelse($courses as $course)
        <a href="{{ route('courses.show', $course) }}" class="list-group-item list-group-item-action d-flex justify-content-between">
            <span>{{ $course->title }}</span>
            <span class="badge bg-secondary">{{ $course->enrollments_count }} learners</span>
        </a>
    @empty
        <div class="list-group-item text-muted">Create courses via the API or database for now.</div>
    @endforelse
</div>

<a href="{{ route('instructor.courses') }}" class="btn btn-outline-primary">All my courses</a>
@endsection
