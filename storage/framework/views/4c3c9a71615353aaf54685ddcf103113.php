<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Admin</span>
        <h1 class="h3 mb-1">Platform overview</h1>
        <p class="text-muted mb-0">Manage users, courses, learning content, and performance signals.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo e(route('admin.courses')); ?>" class="btn btn-outline-primary"><i class="fas fa-layer-group me-2"></i>Courses</a>
        <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-primary"><i class="fas fa-users me-2"></i>Users</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
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
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="brand-mark m-0"><i class="fas fa-<?php echo e($s['icon']); ?>"></i></span>
                    <div>
                        <div class="text-muted small"><?php echo e($s['label']); ?></div>
                        <div class="h4 mb-0"><?php echo e($s['value']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Latest users</h2>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo e($u->name); ?></strong>
                                <div class="small text-muted"><?php echo e($u->email); ?></div>
                            </div>
                            <span class="badge bg-light text-dark"><?php echo e($u->role); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Top enrolled courses</h2>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $topCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('courses.show', $course)); ?>" class="list-group-item list-group-item-action px-0 d-flex justify-content-between">
                            <span><?php echo e($course->title); ?></span>
                            <span class="badge bg-primary"><?php echo e($course->enrollments_count); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-muted small">No enrollments yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Published course health</h2>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $activeCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between gap-2">
                                <strong><?php echo e($course->title); ?></strong>
                                <span class="badge bg-light text-dark"><?php echo e(number_format((float) $course->rating, 1)); ?></span>
                            </div>
                            <div class="small text-muted">
                                <?php echo e($course->lessons_count); ?> lessons · <?php echo e($course->quizzes_count); ?> quizzes · <?php echo e($course->enrollments_count); ?> enrollments
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>