@extends('layouts.app')

@section('title', $exercise->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('lessons.show', $exercise->lesson_id) }}" class="text-decoration-none small">← {{ $exercise->lesson->title }}</a>
</div>

<h1 class="h3 mb-2">{{ $exercise->title }}</h1>
<p class="text-muted small">{{ str_replace('_', ' ', $exercise->exercise_type) }} · {{ $exercise->points }} pts</p>

<div class="card mb-4">
    <div class="card-body">
        <p>{!! nl2br(e($exercise->description)) !!}</p>
        @if($exercise->instructions)
            <p class="mb-0"><strong>Instructions:</strong> {!! nl2br(e($exercise->instructions)) !!}</p>
        @endif
    </div>
</div>

@if($exercise->exercise_type === 'matching' && $exercise->content)
    @php
        $pairs = json_decode($exercise->content, true);
    @endphp
    @if(is_array($pairs))
        <div class="card mb-4">
            <div class="card-header">Pairs to match</div>
            <ul class="list-group list-group-flush">
                @foreach($pairs as $left => $right)
                    <li class="list-group-item"><strong>{{ $left }}</strong> → {{ $right }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@else
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6">Task</h2>
            <div class="small">{!! nl2br(e(is_string($exercise->content) ? $exercise->content : json_encode($exercise->content))) !!}</div>
        </div>
    </div>
@endif

@if($submission)
    <div class="alert {{ $submission->status === 'graded' ? 'alert-success' : 'alert-secondary' }}">
        <strong>Latest submission</strong> ({{ $submission->submitted_at?->diffForHumans() }})
        <p class="mb-1 mt-2 small">{{ \Illuminate\Support\Str::limit($submission->submission_content, 400) }}</p>
        @if($submission->status === 'graded')
            <p class="mb-0"><strong>Score:</strong> {{ $submission->score }} / {{ $exercise->points }}</p>
            @if($submission->feedback)
                <p class="mb-0 mt-2"><strong>Instructor feedback:</strong> {{ $submission->feedback }}</p>
            @endif
        @else
            <p class="mb-0 small">Awaiting instructor review.</p>
        @endif
    </div>
@endif

<h2 class="h5 mt-4">Your answer</h2>
<form action="{{ route('exercises.submit', $exercise) }}" method="POST">
    @csrf
    <textarea name="submission_content" rows="8" class="form-control" required placeholder="Type your response here…">{{ old('submission_content') }}</textarea>
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
@endsection
