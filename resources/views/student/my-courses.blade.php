@extends('layouts.app')

@section('title', 'My courses')

@section('content')
<h1 class="h3 mb-4">My courses</h1>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Course</th>
                <th>Instructor</th>
                <th>Status</th>
                <th>Progress</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->course->title }}</td>
                    <td>{{ $enrollment->course->instructor->name ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}</td>
                    <td style="min-width: 140px;">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ min(100, $enrollment->completion_percentage) }}%"></div>
                        </div>
                        <small class="text-muted">{{ round($enrollment->completion_percentage, 1) }}%</small>
                    </td>
                    <td><a href="{{ route('courses.show', $enrollment->course_id) }}" class="btn btn-sm btn-primary">Continue</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No enrollments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $enrollments->links() }}
@endsection
