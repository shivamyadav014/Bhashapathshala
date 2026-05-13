<?php $__env->startSection('title', $course->title); ?>

<?php $__env->startSection('extra-css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $totalMinutes = $course->lessons->sum('duration_minutes');
    $totalQuestions = $course->quizzes->sum('total_questions');
    $lessonTotal = $course->lessons->count();
    $quizTotal = $course->quizzes->count();
?>

<div class="mb-4">
    <a href="<?php echo e(route('courses.index')); ?>" class="text-decoration-none small"><i class="fas fa-arrow-left me-1"></i>All courses</a>
</div>

<?php if(auth()->guard()->check()): ?>
    <?php
        $u = auth()->user();
        $canStudy = $u->isAdmin()
            || ($u->role === 'instructor' && (int) $u->id === (int) $course->instructor_id)
            || ($u->role === 'student' && $enrollment);
    ?>
<?php endif; ?>

<section class="course-hero">
    <div class="course-hero-copy">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="meta-pill"><i class="fas fa-language"></i><?php echo e($course->language); ?></span>
            <span class="meta-pill"><i class="fas fa-signal"></i><?php echo e($course->level); ?></span>
            <span class="meta-pill"><i class="fas fa-book-open"></i><?php echo e($course->lessons->count()); ?> lessons</span>
        </div>
        <h1 class="display-6 fw-bold mb-3"><?php echo e($course->title); ?></h1>
        <p class="text-muted mb-4"><i class="fas fa-chalkboard-user me-1"></i><?php echo e($course->instructor->name ?? 'Instructor coming soon'); ?></p>
        <div class="mb-0"><?php echo nl2br(e($course->description)); ?></div>

        <div class="course-overview-grid">
            <div class="course-overview-item">
                <i class="fas fa-book-open"></i>
                <strong><?php echo e($lessonTotal); ?></strong>
                <span>Lessons</span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-clock"></i>
                <strong><?php echo e($totalMinutes ?: 'Self'); ?></strong>
                <span><?php echo e($totalMinutes ? 'Minutes' : 'Paced'); ?></span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-circle-question"></i>
                <strong><?php echo e($quizTotal); ?></strong>
                <span>Quizzes</span>
            </div>
            <div class="course-overview-item">
                <i class="fas fa-star"></i>
                <strong><?php echo e(number_format((float) $course->rating, 1)); ?></strong>
                <span>Rating</span>
            </div>
        </div>
    </div>

    <div class="course-media course-hero-media <?php echo e($course->thumbnail ? 'has-image' : ''); ?>">
        <?php if($course->thumbnail): ?>
            <img src="<?php echo e($course->thumbnail); ?>" alt="<?php echo e($course->title); ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentElement.classList.remove('has-image'); this.remove();">
        <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $course->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <span class="d-flex align-items-center">
                                <span class="number-badge"><?php echo e($lesson->order); ?></span>
                                <span>
                                    <strong><?php echo e($lesson->title); ?></strong>
                                    <span class="lesson-meta">
                                        <?php if($lesson->duration_minutes): ?>
                                            <span class="meta-pill"><i class="fas fa-clock"></i><?php echo e($lesson->duration_minutes); ?> min</span>
                                        <?php endif; ?>
                                        <?php if($lesson->exercises_count ?? false): ?>
                                            <span class="meta-pill"><i class="fas fa-pen"></i><?php echo e($lesson->exercises_count); ?> exercises</span>
                                        <?php endif; ?>
                                    </span>
                                    <?php if(auth()->guard()->check()): ?>
                                        <?php if(isset($enrollment) && isset($completedLessonIds) && $completedLessonIds->contains($lesson->id)): ?>
                                            <span class="badge bg-success ms-1">Done</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </span>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(!empty($canStudy)): ?>
                                    <a href="<?php echo e(route('lessons.show', $lesson)); ?>" class="btn btn-sm btn-outline-primary">Open</a>
                                <?php else: ?>
                                    <span class="small text-muted">Enroll first</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-secondary">Login to access</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item text-muted">No lessons yet.</li>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $course->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <strong><?php echo e($quiz->title); ?></strong>
                                <div class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($quiz->description, 90)); ?></div>
                                <div class="lesson-meta">
                                    <span class="meta-pill"><i class="fas fa-list-check"></i><?php echo e($quiz->total_questions); ?> questions</span>
                                    <?php if($quiz->time_limit_minutes): ?>
                                        <span class="meta-pill"><i class="fas fa-clock"></i><?php echo e($quiz->time_limit_minutes); ?> min</span>
                                    <?php endif; ?>
                                    <span class="meta-pill"><i class="fas fa-bullseye"></i><?php echo e($quiz->passing_score); ?>% pass</span>
                                </div>
                            </div>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(!empty($canStudy)): ?>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="<?php echo e(route('quizzes.show', $quiz)); ?>" class="btn btn-sm btn-primary">Take quiz</a>
                                        <a href="<?php echo e(route('quizzes.history', $quiz)); ?>" class="btn btn-sm btn-outline-secondary">Attempts</a>
                                    </div>
                                <?php else: ?>
                                    <span class="small text-muted">Enroll to open quizzes.</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-secondary">Login</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item text-muted">No quizzes yet.</li>
                    <?php endif; ?>
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
                    <li><i class="fas fa-circle-check"></i><span><?php echo e($totalQuestions); ?> quiz questions to check understanding</span></li>
                    <li><i class="fas fa-chart-line"></i><span>Progress tracking after enrollment</span></li>
                </ul>

                <?php if(auth()->guard()->check()): ?>
                    <?php if($enrollment): ?>
                        <p class="mb-2"><strong>Your progress</strong></p>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar" style="width: <?php echo e(min(100, $enrollment->completion_percentage)); ?>%"></div>
                        </div>
                        <?php
                            $doneCount = isset($completedLessonIds) ? $completedLessonIds->count() : 0;
                            $lessonTotal = $course->lessons->count();
                        ?>
                        <p class="small text-muted mb-3">
                            <?php echo e(round($enrollment->completion_percentage, 1)); ?>% complete &middot; <?php echo e(ucfirst(str_replace('_', ' ', $enrollment->status))); ?>

                            <?php if($lessonTotal > 0): ?>
                                <br><span><?php echo e($doneCount); ?> of <?php echo e($lessonTotal); ?> lessons completed</span>
                            <?php endif; ?>
                        </p>
                        <a href="#lessons" class="btn btn-outline-primary w-100">Continue learning</a>
                    <?php else: ?>
                        <?php if(auth()->user()->role === 'student'): ?>
                            <h2 class="h5">Ready to begin?</h2>
                            <p class="small text-muted">Enroll to unlock lessons, quizzes, and progress tracking.</p>
                            <form action="<?php echo e(route('courses.enroll', $course)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success w-100">Enroll in this course</button>
                            </form>
                        <?php else: ?>
                            <p class="small text-muted mb-0">Use a learner account to enroll and track progress.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <h2 class="h5">Track your learning</h2>
                    <p class="small text-muted">Log in to enroll and save progress across lessons, exercises, and quizzes.</p>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary w-100 mt-2">Create account</a>
                <?php endif; ?>

                <button type="button" id="copy-course-link" class="btn btn-outline-secondary w-100 mt-3">
                    <i class="fas fa-link me-2"></i>Copy course link
                </button>
                <div id="copy-course-feedback" class="small text-success mt-2 d-none">Course link copied.</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-js'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/courses/show.blade.php ENDPATH**/ ?>