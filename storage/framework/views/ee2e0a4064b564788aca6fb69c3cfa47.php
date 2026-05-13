<?php $__env->startSection('title', 'Admin - Courses'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Manage</span>
        <h1 class="h3 mb-1">Courses</h1>
        <p class="text-muted mb-0">Review catalog health and publish or unpublish courses.</p>
    </div>
    <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline-primary"><i class="fas fa-eye me-2"></i>View catalog</a>
</div>

<form method="GET" class="card mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-5">
                <input type="search" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search title, language, level">
            </div>
            <div class="col-md-3 col-lg-2">
                <select name="status" class="form-select">
                    <option value="">All status</option>
                    <option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>Published</option>
                    <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-3">
                <select name="language" class="form-select">
                    <option value="">All languages</option>
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($language); ?>" <?php if(request('language') === $language): echo 'selected'; endif; ?>><?php echo e($language); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6 col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filter</button>
                <a href="<?php echo e(route('admin.courses')); ?>" class="btn btn-outline-secondary">Reset</a>
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
                <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($course->title); ?></strong>
                            <div class="small text-muted"><?php echo e($course->language); ?> · <?php echo e($course->level); ?></div>
                        </td>
                        <td><?php echo e($course->instructor->name ?? 'Unassigned'); ?></td>
                        <td class="small text-muted">
                            <?php echo e($course->lessons_count); ?> lessons · <?php echo e($course->quizzes_count); ?> quizzes · <?php echo e($course->enrollments_count); ?> enrollments
                        </td>
                        <td><?php echo e(number_format((float) $course->rating, 1)); ?></td>
                        <td>
                            <?php if($course->is_published): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="<?php echo e(route('courses.show', $course)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="<?php echo e(route('admin.courses.status', array_merge(['course' => $course], request()->query()))); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="is_published" value="<?php echo e($course->is_published ? 0 : 1); ?>">
                                    <button class="btn btn-sm <?php echo e($course->is_published ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                        <?php echo e($course->is_published ? 'Unpublish' : 'Publish'); ?>

                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-muted text-center py-4">No courses match your filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3"><?php echo e($courses->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/admin/courses.blade.php ENDPATH**/ ?>