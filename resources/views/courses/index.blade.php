@extends('layouts.app')

@section('title', 'All courses')

@section('extra-css')
<style>
    .catalog-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(260px, .65fr);
        gap: 1.25rem;
        align-items: stretch;
        padding: clamp(1.35rem, 4vw, 2.5rem);
        margin-bottom: 1.25rem;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(22, 78, 99, .95), rgba(36, 84, 214, .9)),
            repeating-linear-gradient(45deg, rgba(255,255,255,.13) 0 1px, transparent 1px 18px);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .catalog-hero h1 {
        max-width: 760px;
        font-weight: 800;
    }

    .catalog-hero p {
        max-width: 700px;
        color: rgba(255, 255, 255, .84);
    }

    .catalog-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .catalog-hero-panel {
        display: grid;
        gap: .75rem;
        align-content: center;
    }

    .catalog-proof {
        padding: .85rem;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 8px;
    }

    .catalog-proof strong {
        display: block;
        font-size: 1.35rem;
        line-height: 1;
    }

    .catalog-proof span {
        color: rgba(255, 255, 255, .78);
        font-size: .82rem;
    }

    .category-strip {
        display: flex;
        gap: .65rem;
        overflow-x: auto;
        padding: .15rem .05rem 1rem;
        margin-bottom: .5rem;
    }

    .category-chip {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        gap: .45rem;
        padding: .55rem .8rem;
        color: #344054;
        background: rgba(255, 255, 255, .92);
        border: 1px solid var(--line);
        border-radius: 999px;
        font-weight: 700;
        box-shadow: 0 10px 22px rgba(24, 34, 48, .06);
    }

    .category-chip:hover,
    .category-chip.active {
        color: var(--primary);
        border-color: #b8c8df;
        background: #eaf2ff;
    }

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

    .course-card {
        overflow: hidden;
    }

    .course-card .card-body {
        position: relative;
    }

    .course-badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: .45rem;
        margin-bottom: .85rem;
    }

    .course-provider {
        display: flex;
        align-items: center;
        gap: .55rem;
        margin-bottom: .7rem;
        color: var(--muted);
        font-size: .88rem;
        font-weight: 600;
    }

    .provider-avatar {
        display: inline-grid;
        width: 1.8rem;
        height: 1.8rem;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 800;
    }

    .course-rating-line {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: #344054;
        font-size: .9rem;
        font-weight: 700;
    }

    .course-rating-line i {
        color: #f59e0b;
    }

    .course-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: .6rem;
        align-items: center;
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
        .catalog-hero {
            grid-template-columns: 1fr;
        }

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
    $lessonTotal = $courses->sum(fn ($course) => (int) ($course->published_lessons_count ?? 0));
    $quizTotal = $courses->sum(fn ($course) => (int) ($course->published_quizzes_count ?? 0));
    $topLanguages = $languages->take(6);
@endphp

<section class="catalog-hero">
    <div>
        <span class="badge bg-light text-primary mb-3">LinguaLift catalog</span>
        <h1 class="display-6 mb-3">Build language skills with guided courses, practice, and progress tracking.</h1>
        <p class="mb-4">Explore structured paths by language and level, then continue with lessons, quizzes, and performance feedback from your learner dashboard.</p>
        <div class="catalog-hero-actions">
            <a href="#course-grid" class="btn btn-light"><i class="fas fa-compass me-2"></i>Explore courses</a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light"><i class="fas fa-user-plus me-2"></i>Join free</a>
            @endguest
        </div>
    </div>
    <div class="catalog-hero-panel">
        <div class="catalog-proof">
            <strong>{{ $courses->count() }}</strong>
            <span>published courses</span>
        </div>
        <div class="catalog-proof">
            <strong>{{ $lessonTotal }}</strong>
            <span>guided lessons</span>
        </div>
        <div class="catalog-proof">
            <strong>{{ $quizTotal }}</strong>
            <span>knowledge checks</span>
        </div>
    </div>
</section>

@if($topLanguages->isNotEmpty())
    <div class="category-strip" aria-label="Popular language filters">
        <button type="button" class="category-chip active" data-language-chip="">
            <i class="fas fa-border-all"></i>All
        </button>
        @foreach($topLanguages as $language)
            <button type="button" class="category-chip" data-language-chip="{{ strtolower($language) }}">
                <i class="fas fa-language"></i>{{ $language }}
            </button>
        @endforeach
    </div>
@endif

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
                    <div class="course-badge-row">
                        <span class="meta-pill"><i class="fas fa-language"></i>{{ $course->language }}</span>
                        <span class="meta-pill"><i class="fas fa-signal"></i>{{ ucfirst($course->level) }}</span>
                    </div>
                    <h2 class="h5 card-title mb-2">{{ $course->title }}</h2>
                    <div class="course-provider">
                        <span class="provider-avatar">{{ strtoupper(substr($course->instructor->name ?? 'LL', 0, 1)) }}</span>
                        <span>{{ $course->instructor->name ?? 'LinguaLift instructor' }}</span>
                    </div>
                    <p class="card-text small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($course->description, 110) }}</p>

                    <div class="course-rating-line mb-2">
                        <i class="fas fa-star"></i>
                        <span>{{ number_format((float) $course->rating, 1) }}</span>
                        <span class="text-muted fw-semibold">course rating</span>
                    </div>

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

                    <div class="course-actions mt-auto">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary">View course</a>
                        <span class="small text-muted"><i class="fas fa-certificate me-1"></i>Trackable</span>
                    </div>
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
        const chips = Array.from(document.querySelectorAll('[data-language-chip]'));

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
            chips.forEach((chip) => chip.classList.toggle('active', chip.dataset.languageChip === selectedLanguage));
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

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                language.value = chip.dataset.languageChip;
                applyFilters();
            });
        });
    });
</script>
@endsection
