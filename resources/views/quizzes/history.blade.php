@extends('layouts.app')

@section('title', 'Quiz attempts')

@section('content')
<div class="mb-3">
    <a href="{{ route('courses.show', $quiz->course_id) }}" class="text-decoration-none small">← Course</a>
</div>

<h1 class="h3 mb-4">Attempts: {{ $quiz->title }}</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Completed</th>
            <th>Score</th>
            <th>Grade</th>
            <th>Result</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $r)
            <tr>
                <td>{{ $r->completed_at?->format('Y-m-d H:i') }}</td>
                <td>{{ round($r->score, 1) }}%</td>
                <td>{{ $r->getGrade() }}</td>
                <td>
                    @if($r->passed)
                        <span class="badge bg-success">Passed</span>
                    @else
                        <span class="badge bg-secondary">Not passed</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary">Take quiz again</a>
@endsection
