<?php $__env->startSection('title', 'Admin - Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Reports</span>
        <h1 class="h3 mb-1">Platform reports</h1>
        <p class="text-muted mb-0">Snapshot of users, content, courses, and quiz outcomes.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Users</h2>
            <div class="h3"><?php echo e($userStats['total']); ?></div>
            <div class="small text-muted">Students <?php echo e($userStats['students']); ?> · Instructors <?php echo e($userStats['instructors']); ?> · Admins <?php echo e($userStats['admins']); ?></div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Courses</h2>
            <div class="h3"><?php echo e($courseStats['total']); ?></div>
            <div class="small text-muted">Published <?php echo e($courseStats['published']); ?> · Drafts <?php echo e($courseStats['draft']); ?></div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Content</h2>
            <div class="h3"><?php echo e($contentStats['lessons']); ?></div>
            <div class="small text-muted"><?php echo e($contentStats['exercises']); ?> exercises · <?php echo e($contentStats['quizzes']); ?> quizzes · <?php echo e($contentStats['questions']); ?> questions</div>
        </div></div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100"><div class="card-body">
            <h2 class="h6 text-muted">Quizzes</h2>
            <div class="h3"><?php echo e($quizStats['pass_rate']); ?>%</div>
            <div class="small text-muted"><?php echo e($quizStats['total_attempts']); ?> attempts · <?php echo e($quizStats['average_score']); ?>% average score</div>
        </div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Courses by language</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Language</th><th>Total</th><th>Published</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $languageStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($row->language); ?></td>
                                    <td><?php echo e($row->total); ?></td>
                                    <td><?php echo e($row->published); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Top courses by enrollment</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Course</th><th>Language</th><th>Enrollments</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $topCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($course->title); ?></td>
                                    <td><?php echo e($course->language); ?></td>
                                    <td><?php echo e($course->enrollments_count); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/admin/reports.blade.php ENDPATH**/ ?>