

<?php $__env->startSection('title', $lesson->title); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <a href="<?php echo e(route('courses.show', $lesson->course_id)); ?>" class="text-decoration-none small">← <?php echo e($lesson->course->title); ?></a>
</div>

<?php if($lesson->cover_image): ?>
    <div class="rounded-3 overflow-hidden mb-4 shadow-sm" style="max-height: 300px;">
        <img src="<?php echo e($lesson->cover_image); ?>" alt="" class="w-100 d-block" style="max-height: 300px; object-fit: cover;" loading="lazy" referrerpolicy="no-referrer">
    </div>
<?php endif; ?>

<h1 class="h3 mb-3"><?php echo e($lesson->title); ?></h1>
<?php if($lesson->duration_minutes): ?>
    <p class="text-muted small">About <?php echo e($lesson->duration_minutes); ?> min</p>
<?php endif; ?>

<?php if($progress?->is_completed): ?>
    <div class="alert alert-success py-2">You completed this lesson.</div>
<?php elseif($progress): ?>
    <div class="alert alert-info py-2">Progress: <?php echo e(round($progress->progress_percentage)); ?>%</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <div class="lesson-content"><?php echo nl2br(e($lesson->content)); ?></div>
        <?php if($lesson->notes): ?>
            <hr>
            <h2 class="h6">Notes</h2>
            <div class="small text-muted"><?php echo nl2br(e($lesson->notes)); ?></div>
        <?php endif; ?>
    </div>
</div>

<h2 class="h5">Exercises</h2>
<ul class="list-group mb-4">
    <?php $__empty_1 = true; $__currentLoopData = $lesson->exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong><?php echo e($ex->title); ?></strong>
                <span class="badge bg-light text-dark ms-1"><?php echo e(str_replace('_', ' ', $ex->exercise_type)); ?></span>
            </div>
            <a href="<?php echo e(route('exercises.show', $ex)); ?>" class="btn btn-sm btn-outline-primary">Start</a>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class="list-group-item text-muted">No exercises for this lesson.</li>
    <?php endif; ?>
</ul>

<?php if(auth()->user()->role === 'student' || auth()->user()->isInstructor() || auth()->user()->isAdmin()): ?>
    <form action="<?php echo e(route('lessons.complete', $lesson)); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary" <?php if($progress?->is_completed): ?> disabled <?php endif; ?>>
            Mark lesson complete
        </button>
    </form>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/lessons/show.blade.php ENDPATH**/ ?>