<?php
$variant = $variant ?? 'text';
$label = $label ?? '';
$id = $id ?? '';
$name = $name ?? ($id ?: '');
$type = $type ?? 'text';
$autocomplete = $autocomplete ?? null;
$required = $required ?? false;
$model = $model ?? null; // Alpine x-model var name
$oninput = $oninput ?? null; // Alpine @input expression
$errorKey = $errorKey ?? null; // e.g., 'errors.email'
$descId = $descId ?? ($id ? $id.'-error' : 'input-error');
$withEye = $withEye ?? false;
$eyeRef = $eyeRef ?? null;
$inputClasses = $inputClasses ?? '';
?>

<div>
    <?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo e($label); ?></label>
    <?php endif; ?>
    <div class="relative group focus-within:ring-2 focus-within:ring-primary-500 rounded-lg transition-all duration-200">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" aria-hidden="true">
            <?php switch($variant):
                case ('email'): ?>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <?php break; ?>
                <?php case ('password'): ?>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <?php break; ?>
                <?php case ('name'): ?>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <?php break; ?>
                <?php default: ?>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
            <?php endswitch; ?>
        </span>

        <input id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" type="<?php echo e($type); ?>"
               <?php if($autocomplete): ?> autocomplete="<?php echo e($autocomplete); ?>" <?php endif; ?>
               <?php if($required): ?> required <?php endif; ?>
               <?php if($eyeRef): ?> x-ref="<?php echo e($eyeRef); ?>" <?php endif; ?>
               <?php if($model): ?> x-model="<?php echo e($model); ?>" <?php endif; ?>
               <?php if($oninput): ?> @input="<?php echo e($oninput); ?>" <?php endif; ?>
               <?php if($errorKey): ?> x-bind:aria-invalid="!!<?php echo e($errorKey); ?>" <?php endif; ?>
               aria-describedby="<?php echo e($descId); ?>"
               class="form-input h-12 pl-10 <?php echo e($withEye ? 'pr-10' : ''); ?> text-base <?php echo e($inputClasses); ?> w-full transition-all duration-200 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20">

        <?php if($withEye && $eyeRef): ?>
            <button type="button" @click="const f=$refs.<?php echo e($eyeRef); ?>; f.type=f.type==='password'?'text':'password'" class="absolute inset-y-0 right-0 pr-3 flex items-center" aria-label="Tampilkan password">
                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        <?php endif; ?>
    </div>

    <?php if($errorKey): ?>
        <p x-show="<?php echo e($errorKey); ?>" id="<?php echo e($descId); ?>" class="mt-1 text-xs text-danger-600" x-text="<?php echo e($errorKey); ?>" role="alert"></p>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/auth/partials/input.blade.php ENDPATH**/ ?>