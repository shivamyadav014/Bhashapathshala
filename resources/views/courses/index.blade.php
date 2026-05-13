@extends('layouts.app')

@section('title', 'All courses')

@section('extra-css')
<style>
    .catalog-tools {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 180px 180px 170px;
        gap: .75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, .92);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: 0 14px 36px rgba(24, 34, 48, 0.06);
    }

    .catalog-search {
        position: relative;
    }

    .catalog-search i {
        position: absolute;
        top: 50%;
        left: .85rem;
        color: var(--muted);
        transform: translateY(-50%);
    }

    .catalog-search .form-control {
        padding-left: 2.3rem;
    }

    .catalog-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .course-card[hidden] {
        display: none !important;
    }

    .course-facts {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .4rem;
        margin: .85rem 0 1rem;
    }

    .course-fact {
        padding: .45rem;
        background: #f8fbff;
        border: 1px solid var(--line);
        border-radius: 8px;
        text-align: center;
    }

    .course-fact strong {
        display: block;
        font-size: .92rem;
        line-height: 1.1;
    }

    .course-fact span {
        color: var(--muted);
        font-size: .72rem;
    }

    @media (max-width: 991.98px) {
        .catalog-tools {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .catalog-tools {
            grid-template-columns: 1fr;
        }

        .catalog-summary {
            display: block;
        }
    }
</style>
@endsection

@section('content')
@php
    $languages = $courses->pluck('language')->filter()->unique()->sort()->values();
    $levels = $courses->pluck('level')->filter()->unique()->sort()->values();
@endphp

<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Course catalog</span>
        <h1 class="h3 mb-1">All courses</h1>
        <p class="text-muted mb-0">Search, filter, and pick the right BhashaPathshala path.</p>
    </div>
    @guest
        <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Join free</a>
    @endguest
</div>

<div class="catalog-tools" aria-label="Course filters">
    <div class="catalog-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" id="course-search" class="form-control" placeholder="Search Spanish, beginner, travel...">
    </div>

    <select id="language-filter" class="form-select" aria-label="Filter by language">
        <option value="">All languages</option>
        @foreach($languages as $language)
            <option value="{{ strtolower($language) }}">{{ $language }}</option>
        @endforeach
    </select>

    <select id="level-filter" class="form-select" aria-label="Filter by level">
        <option value="">All levels</option>
        @foreach($levels as $level)
            <option value="{{ strtolower($level) }}">{{ ucfirst($level) }}</option>
        @endforeach
    </select>

    <select id="sort-courses" class="form-select" aria-label="Sort courses">
        <option value="title">Sort by title</option>
        <option value="rating">Highest rated</option>
        <option value="lessons">Most lessons</option>
        <option value="level">Level</option>
    </select>
</div>

<div class="catalog-summary">
    <p class="small text-muted mb-0"><span id="course-result-count">{{ $courses->count() }}</span> courses available</p>
    <button id="reset-course-filters" type="button" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-rotate-right me-1"></i>Reset filters
    </button>
</div>

<div id="course-grid" class="row g-4">
    @forelse($courses as $course)
        <div
            class="col-md-6 col-lg-4 course-card-item"
            data-title="{{ strtolower($course->title) }}"
            data-language="{{ strtolower($course->language) }}"
            data-level="{{ strtolower($course->level) }}"
            data-description="{{ strtolower($course->description) }}"
            data-rating="{{ (float) $course->rating }}"
            data-lessons="{{ (int) ($course->published_lessons_count ?? $course->lessons()->where('is_published', true)->count()) }}"
        >
            <div class="card course-card h-100">
                <div class="course-media {{ $course->thumbnail ? 'has-image' : '' }}">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentElement.classList.remove('has-image'); this.remove();">
                    @endif
                    <i class="fas fa-language"></i>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="meta-pill"><i class="fas fa-language"></i>{{ $course->language }}</span>
                        <span class="meta-pill"><i class="fas fa-signal"></i>{{ $course->level }}</span>
                    </div>
                    <h2 class="h5 card-title mb-2">{{ $course->title }}</h2>
                    <div class="small text-muted mb-2">
                        <i class="fas fa-chalkboard-user me-1"></i>{{ $course->instructor->name ?? 'Instructor coming soon' }}
                    </div>
                    <p class="card-text small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($course->description, 110) }}</p>

                    <div class="course-facts">
                        <div class="course-fact">
                            <strong>{{ $course->published_lessons_count ?? 0 }}</strong>
                            <span>Lessons</span>
                        </div>
                        <div class="course-fact">
                            <strong>{{ $course->published_quizzes_count ?? 0 }}</strong>
                            <span>Quizzes</span>
                        </div>
                        <div class="course-fact">
                            <strong>{{ number_format((float) $course->rating, 1) }}</strong>
                            <span>Rating</span>
                        </div>
                    </div>

                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary w-100 mt-auto">Open course</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No courses available yet.</div>
        </div>
    @endforelse
</div>

<div id="no-course-results" class="alert alert-info mt-4 d-none">
    No courses match your filters. Try another language, level, or search term.
</div>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.getElementById('course-grid');
        const cards = Array.from(document.querySelectorAll('.course-card-item'));
        const search = document.getElementById('course-search');
        const language = document.getElementById('language-filter');
        const level = document.getElementById('level-filter');
        const sort = document.getElementById('sort-courses');
        const count = document.getElementById('course-result-count');
        const empty = document.getElementById('no-course-results');
        const reset = document.getElementById('reset-course-filters');

        const applyFilters = () => {
            const query = search.value.trim().toLowerCase();
            const selectedLanguage = language.value;
            const selectedLevel = level.value;

            let visible = 0;

            cards.forEach((card) => {
                const haystack = `${card.dataset.title} ${card.dataset.language} ${card.dataset.level} ${card.dataset.description}`;
                const matchesSearch = !query || haystack.includes(query);
                const matchesLanguage = !selectedLanguage || card.dataset.language === selectedLanguage;
                const matchesLevel = !selectedLevel || card.dataset.level === selectedLevel;
                const show = matchesSearch && matchesLanguage && matchesLevel;

                card.hidden = !show;
                if (show) visible++;
            });

            count.textContent = visible;
            empty.classList.toggle('d-none', visible !== 0);
        };

        const applySort = () => {
            const sorted = [...cards].sort((a, b) => {
                if (sort.value === 'rating') {
                    return Number(b.dataset.rating) - Number(a.dataset.rating);
                }

                if (sort.value === 'lessons') {
                    return Number(b.dataset.lessons) - Number(a.dataset.lessons);
                }

                if (sort.value === 'level') {
                    return a.dataset.level.localeCompare(b.dataset.level) || a.dataset.title.localeCompare(b.dataset.title);
                }

                return a.dataset.title.localeCompare(b.dataset.title);
            });

            sorted.forEach((card) => grid.appendChild(card));
        };

        [search, language, level].forEach((control) => control.addEventListener('input', applyFilters));
        sort.addEventListener('change', () => {
            applySort();
            applyFilters();
        });

        reset.addEventListener('click', () => {
            search.value = '';
            language.value = '';
            level.value = '';
            sort.value = 'title';
            applySort();
            applyFilters();
        });
    });
</script>
@endsection
