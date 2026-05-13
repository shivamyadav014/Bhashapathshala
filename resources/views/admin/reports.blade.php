@extends('layouts.app')

@section('title', 'Admin - Reports')

@section('content')
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Reports</span>
        <h1 class="h3 mb-1">Platform reports</h1>
        <p class="text-muted mb-0">Snapshot of users, content, courses, and quiz outcomes.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Users</h2>
            <div class="h3">{{ $userStats['total'] }}</div>
            <div class="small text-muted">Students {{ $userStats['students'] }} · Instructors {{ $userStats['instructors'] }} · Admins {{ $userStats['admins'] }}</div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Courses</h2>
            <div class="h3">{{ $courseStats['total'] }}</div>
            <div class="small text-muted">Published {{ $courseStats['published'] }} · Drafts {{ $courseStats['draft'] }}</div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Content</h2>
            <div class="h3">{{ $contentStats['lessons'] }}</div>
            <div class="small text-muted">{{ $contentStats['exercises'] }} exercises · {{ $contentStats['quizzes'] }} quizzes · {{ $contentStats['questions'] }} questions</div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Quizzes</h2>
            <div class="h3">{{ $quizStats['pass_rate'] }}%</div>
            <div class="small text-muted">{{ $quizStats['total_attempts'] }} attempts · {{ $quizStats['average_score'] }}% average score</div>
        </div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Courses by language</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Language</th><th>Total</th><th>Published</th></tr></thead>
                        <tbody>
                            @foreach($languageStats as $row)
                                <tr>
                                    <td>{{ $row->language }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ $row->published }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Top courses by enrollment</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Course</th><th>Language</th><th>Enrollments</th></tr></thead>
                        <tbody>
                            @foreach($topCourses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->language }}</td>
                                    <td>{{ $course->enrollments_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
