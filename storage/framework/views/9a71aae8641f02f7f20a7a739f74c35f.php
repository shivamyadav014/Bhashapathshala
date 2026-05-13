

<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="h3 mb-4">Your profile</h1>
<div class="card shadow-sm" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('profile')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', auth()->user()->name)); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" disabled>
                <div class="form-text">Email cannot be changed here.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="bio">Bio</label>
                <textarea name="bio" id="bio" rows="4" class="form-control"><?php echo e(old('bio', auth()->user()->bio)); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="profile_image">Profile image URL</label>
                <input type="url" name="profile_image" id="profile_image" class="form-control" value="<?php echo e(old('profile_image', auth()->user()->profile_image)); ?>" placeholder="https://">
            </div>
            <div class="mb-3">
                <label class="form-label" for="language_level">Language Level</label>
                <select name="language_level" id="language_level" class="form-select">
                    <option value="beginner" <?php if(old('language_level', auth()->user()->language_level) === 'beginner'): echo 'selected'; endif; ?>>Beginner</option>
                    <option value="intermediate" <?php if(old('language_level', auth()->user()->language_level) === 'intermediate'): echo 'selected'; endif; ?>>Intermediate</option>
                    <option value="advanced" <?php if(old('language_level', auth()->user()->language_level) === 'advanced'): echo 'selected'; endif; ?>>Advanced</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="goals">Learning Goals <span class="text-muted small">(optional)</span></label>
                <input type="text" name="goals" id="goals" class="form-control" value="<?php echo e(old('goals', auth()->user()->goals)); ?>" placeholder="e.g. Speak fluently, pass an exam">
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\shiva\OneDrive\Desktop\laravel prfoject\resources\views/profile/show.blade.php ENDPATH**/ ?>