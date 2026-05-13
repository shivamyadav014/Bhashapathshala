<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('extra-css'); ?>
<style>
    .home-hero {
        position: relative;
        display: grid;
        min-height: 430px;
        align-items: center;
        overflow: hidden;
        padding: clamp(2rem, 6vw, 4.5rem);
        border: 1px solid var(--line);
        border-radius: 8px;
        background:
            linear-gradient(110deg, rgba(14, 31, 63, .90), rgba(14, 31, 63, .68)),
            url("https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80") center/cover;
        color: #fff;
        box-shadow: var(--shadow);
    }

    .home-hero h1 {
        max-width: 720px;
        font-weight: 850;
    }

    .home-hero p {
        max-width: 610px;
        color: rgba(255, 255, 255, .82);
    }

    .hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 1.5rem;
    }

    .hero-stat {
        min-width: 135px;
        padding: .75rem .9rem;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .feature-band {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .feature-item {
        padding: 1.25rem;
        background: rgba(255, 255, 255, .9);
        border: 1px solid var(--line);
        border-radius: 8px;
    }

    .feature-item i {
        color: var(--accent);
    }

    @media (max-width: 767.98px) {
        .home-hero {
            min-height: 520px;
            padding: 1.5rem;
        }

        .feature-band {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="home-hero mb-5">
    <div>
        <span class="badge bg-light text-dark mb-3">Indian language learning, made practical</span>
        <h1 class="display-4 mb-3">Learn languages with lessons, exercises, and progress that stays visible.</h1>
        <p class="lead mb-0">
            Build daily fluency through structured courses, guided practice, quizzes, and feedback designed for steady improvement.
        </p>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg"><i class="fas fa-arrow-right me-2"></i>Get started</a>
                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-light btn-lg"><i class="fas fa-compass me-2"></i>Browse courses</a>
            <?php else: ?>
                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-primary btn-lg"><i class="fas fa-compass me-2"></i>Browse all courses</a>
            <?php endif; ?>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="h4 mb-0"><?php echo e($courses->count()); ?>+</div>
                <div class="small">featured courses</div>
            </div>
            <div class="hero-stat">
                <div class="h4 mb-0">Practice</div>
                <div class="small">lessons and quizzes</div>
            </div>
            <div class="hero-stat">
                <div class="h4 mb-0">Track</div>
                <div class="small">progress by course</div>
            </div>
        </div>
    </div>
</section>

<section class="feature-band mb-5">
    <div class="feature-item">
        <i class="fas fa-route mb-3"></i>
        <h2 class="h6">Clear learning paths</h2>
        <p class="small text-muted mb-0">Courses are grouped by language and level so learners can choose the right next step.</p>
    </div>
    <div class="feature-item">
        <i class="fas fa-pen-nib mb-3"></i>
        <h2 class="h6">Active practice</h2>
        <p class="small text-muted mb-0">Exercises and quizzes help learners turn reading into recall, writing, and confidence.</p>
    </div>
    <div class="feature-item">
        <i class="fas fa-chart-line mb-3"></i>
        <h2 class="h6">Progress feedback</h2>
        <p class="small text-muted mb-0">Completion, quiz attempts, and performance views keep improvement easy to understand.</p>
    </div>
</section>

<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Featured</span>
        <h2 class="h4 mb-0">Start with a course</h2>
    </div>
    <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline-primary">View all courses</a>
</div>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4">
            <div class="card course-card h-100">
                <div class="course-media <?php echo e($course->thumbnail ? 'has-image' : ''); ?>">
                    <?php if($course->thumbnail): ?>
                        <img src="<?php echo e($course->thumbnail); ?>" alt="<?php echo e($course->title); ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentElement.classList.remove('has-image'); this.remove();">
                    <?php endif; ?>
                    <i class="fas fa-language"></i>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="meta-pill"><i class="fas fa-language"></i><?php echo e($course->language); ?></span>
                        <span class="meta-pill"><i class="fas fa-signal"></i><?php echo e($course->level); ?></span>
                    </div>
                    <h3 class="h5 card-title"><?php echo e($course->title); ?></h3>
                    <p class="card-text text-muted small flex-grow-1"><?php echo e(\Illuminate\Support\Str::limit($course->description, 120)); ?></p>
                    <a href="<?php echo e(route('courses.show', $course)); ?>" class="btn btn-outline-primary mt-auto">View course</a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info mb-0">No published courses yet.</div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/welcome.blade.php ENDPATH**/ ?>