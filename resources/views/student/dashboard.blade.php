@extends('layouts.app')

@section('title', 'Dashboard')

@section('extra-css')
<style>
    .learner-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(260px, .65fr);
        gap: 1.25rem;
        align-items: stretch;
        padding: clamp(1.35rem, 4vw, 2.35rem);
        margin-bottom: 1.5rem;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(15, 159, 143, .95), rgba(36, 84, 214, .9)),
            repeating-linear-gradient(45deg, rgba(255,255,255,.14) 0 1px, transparent 1px 18px);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .learner-hero h1 {
        max-width: 760px;
        font-weight: 800;
    }

    .learner-hero p {
        max-width: 680px;
        color: rgba(255, 255, 255, .84);
    }

    .learner-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .learning-score {
        display: grid;
        align-content: center;
        gap: .65rem;
        padding: 1rem;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 8px;
    }

    .learning-score strong {
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        line-height: 1;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .9rem;
        margin-bottom: 1.5rem;
    }

    .stat-tile {
        display: flex;
        gap: .8rem;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, .94);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: 0 12px 30px rgba(24, 34, 48, .06);
    }

    .stat-icon {
        display: inline-grid;
        width: 2.55rem;
        height: 2.55rem;
        flex: 0 0 auto;
        place-items: center;
        color: var(--primary);
        background: #eaf2ff;
        border-radius: 8px;
    }

    .stat-tile strong {
        display: block;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .stat-tile span {
        color: var(--muted);
        font-size: .82rem;
        font-weight: 600;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
        gap: 1rem;
        align-items: start;
    }

    .learning-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--line);
    }

    .learning-card:last-child {
        border-bottom: 0;
    }

    .learning-card-title {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        align-items: center;
        margin-bottom: .35rem;
    }

    .learning-progress {
        height: .6rem;
        background: #eef4f8;
        border-radius: 999px;
        overflow: hidden;
    }

    .learning-progress span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: inherit;
    }

    .path-list {
        display: grid;
        gap: .75rem;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .path-list li {
        display: flex;
        gap: .7rem;
        align-items: flex-start;
        color: #344054;
        font-size: .92rem;
    }

    .path-list i {
        color: var(--accent);
        margin-top: .18rem;
    }

    @media (max-width: 991.98px) {
        .learner-hero,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575.98px) {
        .stat-grid,
        .learning-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $activeEnrollments = $enrollments->where('status', '!=', 'completed')->sortByDesc('completion_percentage');
    $continueEnrollment = $activeEnrollments->first() ?? $enrollments->sortByDesc('completion_percentage')->first();
    $avgCompletion = (float) $stats['avg_completion'];
@endphp

<section class="learner-hero">
    <div>
        <span class="badge bg-light text-primary mb-3">Learner dashboard</span>
        <h1 class="display-6 mb-3">Welcome back, {{ auth()->user()->name }}.</h1>
        <p class="mb-4">Pick up where you left off, track your progress, and keep your next language milestone in sight.</p>
        <div class="learner-hero-actions">
            @if($continueEnrollment)
                <a href="{{ route('courses.show', $continueEnrollment->course_id) }}" class="btn btn-light">
                    <i class="fas fa-play me-2"></i>Continue learning
                </a>
            @endif
            <a href="{{ route('courses.index') }}" class="btn btn-outline-light">
                <i class="fas fa-compass me-2"></i>Browse courses
            </a>
        </div>
    </div>
    <div class="learning-score">
        <span class="small text-uppercase fw-bold">Average progress</span>
        <strong>{{ $stats['avg_completion'] }}%</strong>
        <span>{{ $stats['lessons_completed'] }} lessons completed across {{ $stats['courses_enrolled'] }} courses</span>
    </div>
</section>

<div class="stat-grid">
    <div class="stat-tile">
        <span class="stat-icon"><i class="fas fa-layer-group"></i></span>
        <div><strong>{{ $stats['courses_enrolled'] }}</strong><span>Courses enrolled</span></div>
    </div>
    <div class="stat-tile">
        <span class="stat-icon"><i class="fas fa-circle-check"></i></span>
        <div><strong>{{ $stats['courses_completed'] }}</strong><span>Courses completed</span></div>
    </div>
    <div class="stat-tile">
        <span class="stat-icon"><i class="fas fa-clipboard-question"></i></span>
        <div><strong>{{ $stats['quiz_attempts'] }}</strong><span>Quiz attempts</span></div>
    </div>
    <div class="stat-tile">
        <span class="stat-icon"><i class="fas fa-bullseye"></i></span>
        <div><strong>{{ $stats['quiz_pass_rate'] }}%</strong><span>Quiz pass rate</span></div>
    </div>
</div>

<div class="dashboard-grid">
    <section class="card">
        <div class="card-body p-0">
            <div class="p-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="h5 mb-1">Your learning</h2>
                    <p class="small text-muted mb-0">Continue active courses and keep momentum visible.</p>
                </div>
                <a href="{{ route('student.my-courses') }}" class="btn btn-sm btn-outline-primary">View all</a>
            </div>

            @forelse($enrollments as $enrollment)
                <div class="learning-card">
                    <div>
                        <div class="learning-card-title">
                            <strong>{{ $enrollment->course->title }}</strong>
                            <span class="meta-pill"><i class="fas fa-language"></i>{{ $enrollment->course->language }}</span>
                            <span class="meta-pill"><i class="fas fa-signal"></i>{{ ucfirst($enrollment->course->level) }}</span>
                        </div>
                        <div class="small text-muted mb-2">
                            {{ $enrollment->course->instructor->name ?? 'LinguaLift instructor' }} · {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                        </div>
                        <div class="learning-progress" aria-label="{{ round($enrollment->completion_percentage, 0) }}% complete">
                            <span style="width: {{ min(100, $enrollment->completion_percentage) }}%"></span>
                        </div>
                        <div class="small text-muted mt-2">{{ round($enrollment->completion_percentage, 1) }}% complete</div>
                    </div>
                    <a href="{{ route('courses.show', $enrollment->course_id) }}" class="btn btn-primary">
                        {{ $enrollment->completion_percentage > 0 ? 'Continue' : 'Start' }}
                    </a>
                </div>
            @empty
                <div class="p-4">
                    <h2 class="h5">Start your first course</h2>
                    <p class="text-muted mb-3">Choose a language path and your dashboard will begin tracking progress here.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse courses</a>
                </div>
            @endforelse
        </div>
    </section>

    <aside class="card">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Recommended next steps</h2>
            <ul class="path-list mb-4">
                <li><i class="fas fa-calendar-check"></i><span>Complete one lesson today to keep your weekly rhythm active.</span></li>
                <li><i class="fas fa-repeat"></i><span>Retake quizzes below your target score and review missed questions.</span></li>
                <li><i class="fas fa-compass"></i><span>Use the catalog filters to find the next level after your current path.</span></li>
            </ul>

            <div class="p-3 bg-light border rounded">
                <div class="small text-muted">Learning health</div>
                <div class="h5 mb-1">
                    @if($avgCompletion >= 70)
                        Strong momentum
                    @elseif($avgCompletion >= 35)
                        Building steadily
                    @else
                        Ready for a fresh push
                    @endif
                </div>
                <p class="small text-muted mb-0">Your dashboard updates as you finish lessons, exercises, and quizzes.</p>
            </div>

            <a href="{{ route('student.performance') }}" class="btn btn-outline-primary w-100 mt-3">
                <i class="fas fa-chart-line me-2"></i>Performance report
            </a>
        </div>
    </aside>
</div>
@endsection
