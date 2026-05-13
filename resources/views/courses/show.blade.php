@extends('layouts.app')

@section('title', $course->title)

@section('extra-css')
<style>
    .course-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr);
        gap: 1.5rem;
        align-items: stretch;
        margin-bottom: 2rem;
    }

    .course-hero-copy {
        padding: clamp(1.4rem, 4vw, 2.5rem);
        background: rgba(255, 255, 255, .94);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .course-hero-media {
        min-height: 320px;
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .content-list .list-group-item {
        padding: 1rem;
        border-left: 0;
        border-right: 0;
    }

    .content-list .list-group-item:first-child {
        border-top: 0;
    }

    .content-list .list-group-item:last-child {
        border-bottom: 0;
    }

    .number-badge {
        display: inline-grid;
        min-width: 2rem;
        height: 2rem;
        margin-right: .75rem;
        place-items: center;
        color: #175cd3;
        background: #eaf2ff;
        border-radius: 999px;
        font-weight: 800;
    }

    .course-overview-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .75rem;
        margin: 1.5rem 0 0;
    }

    .course-overview-item {
        padding: .85rem;
        background: #f8fbff;
        border: 1px solid var(--line);
        border-radius: 8px;
    }

    .course-overview-item i {
        color: var(--accent);
        margin-bottom: .55rem;
    }

    .course-overview-item strong {
        display: block;
        font-size: 1.05rem;
        line-height: 1.1;
    }

    .course-overview-item span {
        color: var(--muted);
        font-size: .78rem;
    }

    .lesson-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-top: .35rem;
    }

    .side-feature-list {
        display: grid;
        gap: .65rem;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .side-feature-list li {
        display: flex;
        gap: .65rem;
        align-items: start;
        color: #344054;
        font-size: .9rem;
    }

    .side-feature-list i {
        color: var(--accent);
        margin-top: .18rem;
    }

    @media (max-width: 991.98px) {
        .course-hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .course-overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endsection

@section('content')
@php
    $totalMinutes = $course->lessons->sum('duration_minutes');
    $totalQuestions = $course->quizzes->sum('total_questions');
    $lessonTotal = $course->lessons->count();
    $quizTotal = $course->quizzes->count();
@endphp

<div class="mb-4">
    <a href="{{ route('courses.index') }}" class="text-decoration-none small"><i class="fas fa-arrow-left me-1"></i>All courses</a>
</div>

@auth
    @php
        $u = auth()->user();
        $canStudy = $u->isAdmin()
            || ($u->role === 'instructor' && (int) $u->id === (int) $course->instructor_id)
            || ($u->role === 'student' && $enrollment);
    @endphp
@endauth

<section class="course-hero">
    <div class="course-hero-copy">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="meta-pill"><i class="fas fa-language"></i>{{ $course->language }}</span>
            <span class="meta-pill"><i class="fas fa-signal"></i>{{ $course->level }}</span>
            <span class="meta-pill"><i class="fas fa-book-open"></i>{{ $course->lessons->count() }} lessons</span>
        </div>
        <h1 class="display-6 fw-bold mb-3">{{ $course->title }}</h1>
        <p class="text-muted mb-4"><i class="fas fa-chalkboard-user me-1"></i>{{ $course->instructor->name ?? 'Instructor coming soon' }}</p>
        <div class="mb-0">{!! nl2br(e($course->description)) !!}</div>

        <div class="course-overview-grid">
            <div class="course-overview-item">
                <i class="fas fa-book-open"></i>
                <strong>{{ $lessonTotal }}</strong>
                <span>Lessons</span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-clock"></i>
                <strong>{{ $totalMinutes ?: 'Self' }}</strong>
                <span>{{ $totalMinutes ? 'Minutes' : 'Paced' }}</span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-circle-question"></i>
                <strong>{{ $quizTotal }}</strong>
                <span>Quizzes</span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-star"></i>
                <strong>{{ number_format((float) $course->rating, 1) }}</strong>
                <span>Rating</span>
            </div>
        </div>
    </div>

    <div class="course-media course-hero-media {{ $course->thumbnail ? 'has-image' : '' }}">
        @if($course->thumbnail)
            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentElement.classList.remove('has-image'); this.remove();">
        @endif
        <i class="fas fa-language"></i>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4" id="lessons">
            <div class="card-body p-0">
                <div class="p-4 border-bottom">
                    <h2 class="h5 mb-1">Lessons</h2>
                    <p class="small text-muted mb-0">Follow the course in order and mark progress as you go.</p>
                </div>
                <ul class="list-group list-group-flush content-list">
                    @forelse($course->lessons as $lesson)
                        <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <span class="d-flex align-items-center">
                                <span class="number-badge">{{ $lesson->order }}</span>
                                <span>
                                    <strong>{{ $lesson->title }}</strong>
                                    <span class="lesson-meta">
                                        @if($lesson->duration_minutes)
                                            <span class="meta-pill"><i class="fas fa-clock"></i>{{ $lesson->duration_minutes }} min</span>
                                        @endif
                                        @if($lesson->exercises_count ?? false)
                                            <span class="meta-pill"><i class="fas fa-pen"></i>{{ $lesson->exercises_count }} exercises</span>
                                        @endif
                                    </span>
                                    @auth
                                        @if(isset($enrollment) && isset($completedLessonIds) && $completedLessonIds->contains($lesson->id))
                                            <span class="badge bg-success ms-1">Done</span>
                                        @endif
                                    @endauth
                                </span>
                            </span>
                            @auth
                                @if(!empty($canStudy))
                                    <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                @else
                                    <span class="small text-muted">Enroll first</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login to access</a>
                            @endauth
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No lessons yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="p-4 border-bottom">
                    <h2 class="h5 mb-1">Quizzes</h2>
                    <p class="small text-muted mb-0">Check your understanding and review your attempts.</p>
                </div>
                <ul class="list-group list-group-flush content-list">
                    @forelse($course->quizzes as $quiz)
                        <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <strong>{{ $quiz->title }}</strong>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($quiz->description, 90) }}</div>
                                <div class="lesson-meta">
                                    <span class="meta-pill"><i class="fas fa-list-check"></i>{{ $quiz->total_questions }} questions</span>
                                    @if($quiz->time_limit_minutes)
                                        <span class="meta-pill"><i class="fas fa-clock"></i>{{ $quiz->time_limit_minutes }} min</span>
                                    @endif
                                    <span class="meta-pill"><i class="fas fa-bullseye"></i>{{ $quiz->passing_score }}% pass</span>
                                </div>
                            </div>
                            @auth
                                @if(!empty($canStudy))
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-sm btn-primary">Take quiz</a>
                                        <a href="{{ route('quizzes.history', $quiz) }}" class="btn btn-sm btn-outline-secondary">Attempts</a>
                                    </div>
                                @else
                                    <span class="small text-muted">Enroll to open quizzes.</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login</a>
                            @endauth
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No quizzes yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 5rem;">
            <div class="card-body p-4">
                <h2 class="h5">What you get</h2>
                <ul class="side-feature-list mb-4">
                    <li><i class="fas fa-route"></i><span>Step-by-step lessons in order</span></li>
                    <li><i class="fas fa-pen-to-square"></i><span>Practice activities inside lesson pages</span></li>
                    <li><i class="fas fa-circle-check"></i><span>{{ $totalQuestions }} quiz questions to check understanding</span></li>
                    <li><i class="fas fa-chart-line"></i><span>Progress tracking after enrollment</span></li>
                </ul>

                @auth
                    @if($enrollment)
                        <p class="mb-2"><strong>Your progress</strong></p>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar" style="width: {{ min(100, $enrollment->completion_percentage) }}%"></div>
                        </div>
                        @php
                            $doneCount = isset($completedLessonIds) ? $completedLessonIds->count() : 0;
                            $lessonTotal = $course->lessons->count();
                        @endphp
                        <p class="small text-muted mb-3">
                            {{ round($enrollment->completion_percentage, 1) }}% complete &middot; {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                            @if($lessonTotal > 0)
                                <br><span>{{ $doneCount }} of {{ $lessonTotal }} lessons completed</span>
                            @endif
                        </p>
                        <a href="#lessons" class="btn btn-outline-primary w-100">Continue learning</a>
                    @else
                        @if(auth()->user()->role === 'student')
                            <h2 class="h5">Ready to begin?</h2>
                            <p class="small text-muted">Enroll to unlock lessons, quizzes, and progress tracking.</p>
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">Enroll in this course</button>
                            </form>
                        @else
                            <p class="small text-muted mb-0">Use a learner account to enroll and track progress.</p>
                        @endif
                    @endif
                @else
                    <h2 class="h5">Track your learning</h2>
                    <p class="small text-muted">Log in to enroll and save progress across lessons, exercises, and quizzes.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-2">Create account</a>
                @endauth

                <button type="button" id="copy-course-link" class="btn btn-outline-secondary w-100 mt-3">
                    <i class="fas fa-link me-2"></i>Copy course link
                </button>
                <div id="copy-course-feedback" class="small text-success mt-2 d-none">Course link copied.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const copyButton = document.getElementById('copy-course-link');
        const feedback = document.getElementById('copy-course-feedback');

        copyButton?.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
                feedback.classList.remove('d-none');
                window.setTimeout(() => feedback.classList.add('d-none'), 2200);
            } catch (error) {
                const input = document.createElement('input');
                input.value = window.location.href;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                input.remove();
                feedback.classList.remove('d-none');
                window.setTimeout(() => feedback.classList.add('d-none'), 2200);
            }
        });
    });
</script>
@endsection
