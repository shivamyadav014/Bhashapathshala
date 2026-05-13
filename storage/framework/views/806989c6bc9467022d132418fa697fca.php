

<?php $__env->startSection('title', $exercise->title); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <a href="<?php echo e(route('lessons.show', $exercise->lesson_id)); ?>" class="text-decoration-none small">← <?php echo e($exercise->lesson->title); ?></a>
</div>

<h1 class="h3 mb-2"><?php echo e($exercise->title); ?></h1>
<p class="text-muted small"><?php echo e(str_replace('_', ' ', $exercise->exercise_type)); ?> · <?php echo e($exercise->points); ?> pts</p>

<div class="card mb-4">
    <div class="card-body">
        <p><?php echo nl2br(e($exercise->description)); ?></p>
        <?php if($exercise->instructions): ?>
            <p class="mb-0"><strong>Instructions:</strong> <?php echo nl2br(e($exercise->instructions)); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if($exercise->exercise_type === 'matching' && $exercise->content): ?>
    <?php
        $pairs = json_decode($exercise->content, true);
    ?>
    <?php if(is_array($pairs)): ?>
        <div class="card mb-4">
            <div class="card-header">Pairs to match</div>
            <ul class="list-group list-group-flush">
                <?php $__currentLoopData = $pairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $left => $right): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item"><strong><?php echo e($left); ?></strong> → <?php echo e($right); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6">Task</h2>
            <div class="small"><?php echo nl2br(e(is_string($exercise->content) ? $exercise->content : json_encode($exercise->content))); ?></div>
        </div>
    </div>
<?php endif; ?>

<?php if($submission): ?>
    <div class="alert <?php echo e($submission->status === 'graded' ? 'alert-success' : 'alert-secondary'); ?>">
        <strong>Latest submission</strong> (<?php echo e($submission->submitted_at?->diffForHumans()); ?>)
        <p class="mb-1 mt-2 small"><?php echo e(\Illuminate\Support\Str::limit($submission->submission_content, 400)); ?></p>
        <?php if($submission->status === 'graded'): ?>
            <p class="mb-0"><strong>Score:</strong> <?php echo e($submission->score); ?> / <?php echo e($exercise->points); ?></p>
            <?php if($submission->feedback): ?>
                <p class="mb-0 mt-2"><strong>Instructor feedback:</strong> <?php echo e($submission->feedback); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p class="mb-0 small">Awaiting instructor review.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<h2 class="h5 mt-4">Your answer</h2>
<form action="<?php echo e(route('exercises.submit', $exercise)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <textarea name="submission_content" rows="8" class="form-control" required placeholder="Type your response here…"><?php echo e(old('submission_content')); ?></textarea>
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/exercises/show.blade.php ENDPATH**/ ?>