@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('courses.show', $lesson->course_id) }}" class="text-decoration-none small">← {{ $lesson->course->title }}</a>
</div>

@if($lesson->cover_image)
    <div class="rounded-3 overflow-hidden mb-4 shadow-sm" style="max-height: 300px;">
        <img src="{{ $lesson->cover_image }}" alt="" class="w-100 d-block" style="max-height: 300px; object-fit: cover;" loading="lazy" referrerpolicy="no-referrer">
    </div>
@endif

<h1 class="h3 mb-3">{{ $lesson->title }}</h1>
@if($lesson->duration_minutes)
    <p class="text-muted small">About {{ $lesson->duration_minutes }} min</p>
@endif

@if($progress?->is_completed)
    <div class="alert alert-success py-2">You completed this lesson.</div>
@elseif($progress)
    <div class="alert alert-info py-2">Progress: {{ round($progress->progress_percentage) }}%</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="lesson-content">{!! nl2br(e($lesson->content)) !!}</div>
        @if($lesson->notes)
            <hr>
            <h2 class="h6">Notes</h2>
            <div class="small text-muted">{!! nl2br(e($lesson->notes)) !!}</div>
        @endif
    </div>
</div>

<h2 class="h5">Exercises</h2>
<ul class="list-group mb-4">
    @forelse($lesson->exercises as $ex)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $ex->title }}</strong>
                <span class="badge bg-light text-dark ms-1">{{ str_replace('_', ' ', $ex->exercise_type) }}</span>
            </div>
            <a href="{{ route('exercises.show', $ex) }}" class="btn btn-sm btn-outline-primary">Start</a>
        </li>
    @empty
        <li class="list-group-item text-muted">No exercises for this lesson.</li>
    @endforelse
</ul>

@if(auth()->user()->role === 'student' || auth()->user()->isInstructor() || auth()->user()->isAdmin())
    <form action="{{ route('lessons.complete', $lesson) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary" @if($progress?->is_completed) disabled @endif>
            Mark lesson complete
        </button>
    </form>
@endif
@endsection
