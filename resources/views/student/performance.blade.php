@extends('layouts.app')

@section('title', 'Performance')

@section('content')
<h1 class="h3 mb-4">Performance & feedback</h1>

@if(!empty($insights))
    <div class="alert alert-light border mb-4">
        <div class="fw-semibold mb-2">How you&apos;re doing</div>
        <ul class="mb-0 ps-3">
            @foreach($insights as $line)
                <li>{{ $line }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Average quiz score</div>
                <div class="h4 mb-0">{{ $overall['avg_score'] }}%</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Quizzes passed</div>
                <div class="h4 mb-0">{{ $overall['passed'] }} / {{ $overall['attempts'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Courses completed</div>
                <div class="h4 mb-0">{{ $enrollments->where('status', 'completed')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Exercise submissions</div>
                <div class="h4 mb-0">{{ $exerciseOverall['submissions'] }}</div>
                @if($exerciseOverall['graded'] > 0 && $exerciseOverall['avg_percent'] !== null)
                    <div class="small text-muted mt-1">Avg. (graded): {{ $exerciseOverall['avg_percent'] }}%</div>
                @endif
            </div>
        </div>
    </div>
</div>

<h2 class="h5 mb-3">Course progress</h2>
<ul class="list-group mb-5">
    @forelse($enrollments as $e)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $e->course->title }}</span>
            <span>{{ round($e->completion_percentage, 1) }}% · {{ ucfirst(str_replace('_', ' ', $e->status)) }}</span>
        </li>
    @empty
        <li class="list-group-item text-muted">No enrollments yet. <a href="{{ route('courses.index') }}">Browse courses</a></li>
    @endforelse
</ul>

<h2 class="h5 mb-3">Exercise activity</h2>
<div class="table-responsive mb-5">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Submitted</th>
                <th>Exercise</th>
                <th>Course</th>
                <th>Status</th>
                <th>Score</th>
                <th>Instructor feedback</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exerciseSubmissions as $sub)
                <tr>
                    <td>{{ $sub->submitted_at?->format('Y-m-d') }}</td>
                    <td>{{ $sub->exercise->title ?? '—' }}</td>
                    <td>{{ $sub->exercise->lesson->course->title ?? '—' }}</td>
                    <td>{{ ucfirst($sub->status) }}</td>
                    <td>
                        @if($sub->score !== null && $sub->exercise)
                            {{ round($sub->score, 1) }} / {{ $sub->exercise->points }}
                            <span class="text-muted small">({{ round($sub->getPercentageScore(), 0) }}%)</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="small">{{ $sub->feedback ? \Illuminate\Support\Str::limit($sub->feedback, 120) : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">No exercise submissions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $exerciseSubmissions->links() }}

<h2 class="h5 mb-3">Quiz history</h2>
<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Quiz</th>
                <th>Course</th>
                <th>Score</th>
                <th>Grade</th>
                <th>Passed</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quizResults as $qr)
                <tr>
                    <td>{{ $qr->completed_at?->format('Y-m-d') }}</td>
                    <td>{{ $qr->quiz->title ?? '—' }}</td>
                    <td>{{ $qr->quiz->course->title ?? '—' }}</td>
                    <td>{{ round($qr->score, 1) }}%</td>
                    <td>{{ $qr->getGrade() }}</td>
                    <td>@if($qr->passed)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">No quiz attempts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $quizResults->links() }}
@endsection
