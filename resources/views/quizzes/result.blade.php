@extends('layouts.app')

@section('title', 'Quiz results')

@section('content')
<div class="mb-3">
    <a href="{{ route('courses.show', $quiz->course_id) }}" class="text-decoration-none small">← Back to course</a>
</div>

<h1 class="h3 mb-4">Results: {{ $quiz->title }}</h1>

<div class="card border-{{ $result->passed ? 'success' : 'danger' }} mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="h2 mb-0">{{ round($result->score, 1) }}%</p>
                <p class="text-muted mb-0">Grade: <strong>{{ $result->getGrade() }}</strong> · {{ $result->passed ? 'Passed' : 'Below passing score' }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge fs-6 {{ $result->passed ? 'bg-success' : 'bg-danger' }}">{{ $result->passed ? 'Passed' : 'Not passed' }}</span>
            </div>
        </div>
    </div>
</div>

@if(!$quiz->show_results_immediately && !$feedback)
    <div class="alert alert-info mb-4">
        Your attempt was recorded. Detailed score feedback may be released later according to course settings — check back or ask your instructor if scores look delayed.
    </div>
@endif

@if($feedback)
    <div class="card mb-4">
        <div class="card-header">Feedback</div>
        <div class="card-body">
            <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $feedback }}</pre>
        </div>
    </div>
@endif

<div class="d-flex gap-2">
    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-outline-primary">Retake quiz</a>
    <a href="{{ route('quizzes.history', $quiz) }}" class="btn btn-outline-secondary">All attempts</a>
</div>
@endsection
