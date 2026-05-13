@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">Welcome back, {{ auth()->user()->name }}</h1>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Courses enrolled</div>
                <div class="h4 mb-0">{{ $stats['courses_enrolled'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Completed</div>
                <div class="h4 mb-0">{{ $stats['courses_completed'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Avg. course progress</div>
                <div class="h4 mb-0">{{ $stats['avg_completion'] }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Lessons completed</div>
                <div class="h4 mb-0">{{ $stats['lessons_completed'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Quiz attempts</div>
                <div class="h4 mb-0">{{ $stats['quiz_attempts'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Quiz pass rate</div>
                <div class="h4 mb-0">{{ $stats['quiz_pass_rate'] }}%</div>
            </div>
        </div>
    </div>
</div>

<h2 class="h5 mb-3">Your courses</h2>
<div class="list-group">
    @forelse($enrollments as $enrollment)
        <a href="{{ route('courses.show', $enrollment->course_id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $enrollment->course->title }}</strong>
                <div class="small text-muted">{{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}</div>
            </div>
            <span class="badge bg-primary rounded-pill">{{ round($enrollment->completion_percentage, 0) }}%</span>
        </a>
    @empty
        <div class="list-group-item text-muted">You are not enrolled yet. <a href="{{ route('courses.index') }}">Browse courses</a></div>
    @endforelse
</div>

<div class="mt-4">
    <a href="{{ route('student.performance') }}" class="btn btn-outline-primary">Full performance report</a>
</div>
@endsection
