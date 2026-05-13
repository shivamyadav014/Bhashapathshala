@extends('layouts.app')

@section('title', 'My courses')

@section('content')
<h1 class="h3 mb-4">Courses you teach</h1>

<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Language</th>
            <th>Level</th>
            <th>Published</th>
            <th>Enrollments</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>{{ $course->language }}</td>
                <td>{{ $course->level }}</td>
                <td>@if($course->is_published)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>{{ $course->enrollments_count }}</td>
                <td><a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted">No courses assigned.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $courses->links() }}
@endsection
