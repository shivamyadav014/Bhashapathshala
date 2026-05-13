@extends('layouts.app')

@section('title', 'Admin - Courses')

@section('content')
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Manage</span>
        <h1 class="h3 mb-1">Courses</h1>
        <p class="text-muted mb-0">Review catalog health and publish or unpublish courses.</p>
    </div>
    <a href="{{ route('courses.index') }}" class="btn btn-outline-primary"><i class="fas fa-eye me-2"></i>View catalog</a>
</div>

<form method="GET" class="card mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-5">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search title, language, level">
            </div>
            <div class="col-md-3 col-lg-2">
                <select name="status" class="form-select">
                    <option value="">All status</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-3">
                <select name="language" class="form-select">
                    <option value="">All languages</option>
                    @foreach($languages as $language)
                        <option value="{{ $language }}" @selected(request('language') === $language)>{{ $language }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filter</button>
                <a href="{{ route('admin.courses') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Content</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>
                            <strong>{{ $course->title }}</strong>
                            <div class="small text-muted">{{ $course->language }} · {{ $course->level }}</div>
                        </td>
                        <td>{{ $course->instructor->name ?? 'Unassigned' }}</td>
                        <td class="small text-muted">
                            {{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes · {{ $course->enrollments_count }} enrollments
                        </td>
                        <td>{{ number_format((float) $course->rating, 1) }}</td>
                        <td>
                            @if($course->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="{{ route('admin.courses.status', array_merge(['course' => $course], request()->query())) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_published" value="{{ $course->is_published ? 0 : 1 }}">
                                    <button class="btn btn-sm {{ $course->is_published ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                        {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted text-center py-4">No courses match your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $courses->links() }}</div>
@endsection
