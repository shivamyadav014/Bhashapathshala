<?php $__env->startSection('title', 'Admin - Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <div>
        <span class="badge badge-soft mb-2">Manage</span>
        <h1 class="h3 mb-1">Users</h1>
        <p class="text-muted mb-0">Search accounts, review activity, and update roles.</p>
    </div>
</div>

<form method="GET" class="card mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="search" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search name or email">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    <?php $__currentLoopData = ['student', 'instructor', 'admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role); ?>" <?php if(request('role') === $role): echo 'selected'; endif; ?>><?php echo e(ucfirst($role)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filter</button>
                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Activity</th>
                    <th>Joined</th>
                    <th>Change role</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($user->name); ?></strong>
                            <div class="small text-muted"><?php echo e($user->email); ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark"><?php echo e($user->role); ?></span></td>
                        <td class="small text-muted">
                            <?php echo e($user->enrollments_count); ?> enrollments · <?php echo e($user->quiz_results_count); ?> quiz attempts
                        </td>
                        <td class="small text-muted"><?php echo e($user->created_at?->format('M d, Y')); ?></td>
                        <td style="min-width: 240px;">
                            <form action="<?php echo e(route('admin.users.role', $user)); ?>" method="POST" class="d-flex gap-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <select name="role" class="form-select form-select-sm">
                                    <?php $__currentLoopData = ['student', 'instructor', 'admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role); ?>" <?php if($user->role === $role): echo 'selected'; endif; ?>><?php echo e($role); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-muted text-center py-4">No users match your filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3"><?php echo e($users->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/admin/users.blade.php ENDPATH**/ ?>