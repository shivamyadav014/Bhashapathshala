@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Admin</span>
        <h1 class="h3 mb-1">Platform overview</h1>
        <p class="text-muted mb-0">Manage users, courses, learning content, and performance signals.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.courses') }}" class="btn btn-outline-primary"><i class="fas fa-layer-group me-2"></i>Courses</a>
        <a href="{{ route('admin.users') }}" class="btn btn-primary"><i class="fas fa-users me-2"></i>Users</a>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['icon' => 'users', 'label' => 'Users', 'value' => $stats['total_users']],
        ['icon' => 'graduation-cap', 'label' => 'Students', 'value' => $stats['total_students']],
        ['icon' => 'chalkboard-user', 'label' => 'Instructors', 'value' => $stats['total_instructors']],
        ['icon' => 'layer-group', 'label' => 'Courses', 'value' => $stats['total_courses']],
        ['icon' => 'circle-check', 'label' => 'Published', 'value' => $stats['published_courses']],
        ['icon' => 'book-open', 'label' => 'Lessons', 'value' => $stats['total_lessons']],
        ['icon' => 'pen-to-square', 'label' => 'Exercises', 'value' => $stats['total_exercises']],
        ['icon' => 'circle-question', 'label' => 'Quizzes', 'value' => $stats['total_quizzes']],
        ['icon' => 'clipboard-check', 'label' => 'Quiz attempts', 'value' => $stats['total_quiz_attempts']],
        ['icon' => 'chart-line', 'label' => 'Avg quiz score', 'value' => round($stats['average_quiz_score'], 1).'%'],
        ['icon' => 'user-plus', 'label' => 'Enrollments', 'value' => $stats['total_enrollments']],
    ] as $s)
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="brand-mark m-0"><i class="fas fa-{{ $s['icon'] }}"></i></span>
                    <div>
                        <div class="text-muted small">{{ $s['label'] }}</div>
                        <div class="h4 mb-0">{{ $s['value'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest users</h2>
                <div class="list-group list-group-flush">
                    @foreach($recentUsers as $u)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $u->name }}</strong>
                                <div class="small text-muted">{{ $u->email }}</div>
                            </div>
                            <span class="badge bg-light text-dark">{{ $u->role }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Top enrolled courses</h2>
                <div class="list-group list-group-flush">
                    @forelse($topCourses as $course)
                        <a href="{{ route('courses.show', $course) }}" class="list-group-item list-group-item-action px-0 d-flex justify-content-between">
                            <span>{{ $course->title }}</span>
                            <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                        </a>
                    @empty
                        <div class="text-muted small">No enrollments yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Published course health</h2>
                <div class="list-group list-group-flush">
                    @foreach($activeCourses as $course)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between gap-2">
                                <strong>{{ $course->title }}</strong>
                                <span class="badge bg-light text-dark">{{ number_format((float) $course->rating, 1) }}</span>
                            </div>
                            <div class="small text-muted">
                                {{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes · {{ $course->enrollments_count }} enrollments
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
