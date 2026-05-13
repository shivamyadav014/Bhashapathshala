@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('courses.show', $quiz->course_id) }}" class="text-decoration-none small">← Course</a>
</div>

<h1 class="h3 mb-2">{{ $quiz->title }}</h1>
<p class="text-muted small">{!! nl2br(e($quiz->description)) !!}</p>
<p class="small">Passing score: {{ $quiz->passing_score }}%
    @if($quiz->time_limit_minutes)
        · Time limit: {{ $quiz->time_limit_minutes }} min
    @endif
</p>

@if($questions->isEmpty())
    <div class="alert alert-info">This quiz has no questions yet. Check back later.</div>
@else
<form action="{{ route('quizzes.submit', $quiz) }}" method="POST">
    @csrf
    @foreach($questions->sortBy('order') as $q)
        <div class="card mb-3">
            <div class="card-body">
                <p class="fw-semibold mb-2">{{ $loop->iteration }}. {{ $q['question'] }} <span class="text-muted small">({{ $q['points'] }} pts)</span></p>

                @if($q['question_type'] === 'multiple_choice' && !empty($q['options']))
                    @foreach($q['options'] as $opt)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $q['id'] }}]" id="q{{ $q['id'] }}_{{ $loop->index }}" value="{{ $opt }}">
                            <label class="form-check-label" for="q{{ $q['id'] }}_{{ $loop->index }}">{{ $opt }}</label>
                        </div>
                    @endforeach
                @elseif($q['question_type'] === 'true_false')
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $q['id'] }}]" id="q{{ $q['id'] }}_t" value="true">
                        <label class="form-check-label" for="q{{ $q['id'] }}_t">True</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $q['id'] }}]" id="q{{ $q['id'] }}_f" value="false">
                        <label class="form-check-label" for="q{{ $q['id'] }}_f">False</label>
                    </div>
                @else
                    <textarea name="answers[{{ $q['id'] }}]" rows="3" class="form-control" placeholder="Your answer"></textarea>
                @endif
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Submit quiz</button>
    <a href="{{ route('quizzes.history', $quiz) }}" class="btn btn-link">Past attempts</a>
</form>
@endif
@endsection
