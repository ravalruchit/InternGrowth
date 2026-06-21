<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <p class="ig-eyebrow mb-3">— Get started · free forever for students</p>
    <h1 class="ig-display text-4xl md:text-5xl mb-3">Create account.</h1>
    <p class="text-[var(--ig-muted)] mb-8 text-sm">Your first verified task is 60 seconds away.</p>

    <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div>
            <label for="name" class="block text-sm font-semibold mb-2">Full name</label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                   value="<?php echo e(old('name')); ?>" placeholder="Your name" class="ig-input" />
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('name'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('name')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold mb-2">Email</label>
            <input id="email" name="email" type="email" required autocomplete="username"
                   value="<?php echo e(old('email')); ?>" placeholder="you@college.edu" class="ig-input" />
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">I am a</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="student" class="peer sr-only" checked>
                    <div class="ig-card p-4 peer-checked:border-[var(--ig-ink)] peer-checked:bg-[var(--ig-ink)] peer-checked:text-[var(--ig-bg)] transition-all">
                        <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        <p class="font-semibold text-sm">Student</p>
                        <p class="text-[11.5px] opacity-70 mt-0.5">Build my portfolio</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="startup" class="peer sr-only">
                    <div class="ig-card p-4 peer-checked:border-[var(--ig-ink)] peer-checked:bg-[var(--ig-ink)] peer-checked:text-[var(--ig-bg)] transition-all">
                        <svg class="w-5 h-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5"/></svg>
                        <p class="font-semibold text-sm">Startup</p>
                        <p class="text-[11.5px] opacity-70 mt-0.5">Find verified talent</p>
                    </div>
                </label>
            </div>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('role'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('role')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <!-- Student Career Domain & Role Selection (shown only for students) -->
        <div id="student-career-selection" class="space-y-6" style="display: none;">
            <!-- Hidden original select inputs for form compatibility -->
            <select id="primary_domain" name="primary_domain" class="hidden">
                <option value="">Select Domain</option>
                <?php $__currentLoopData = \App\Models\StudentProfile::$domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain => $roles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($domain); ?>" <?php echo e(old('primary_domain') == $domain ? 'selected' : ''); ?>><?php echo e($domain); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select id="preferred_role" name="preferred_role" class="hidden" disabled>
                <option value="">Select Role</option>
            </select>

            <!-- Domain selection UI -->
            <div>
                <label class="block text-sm font-semibold mb-3">Choose Your Career Domain</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="domain-cards-container">
                    <?php
                        $domainDetails = [
                            'Software Development' => ['icon' => '💻', 'desc' => 'Build web apps, mobile apps, backend systems, and write clean code.'],
                            'UI/UX Design' => ['icon' => '🎨', 'desc' => 'Create visual interfaces, wireframes, prototype flows, and design systems.'],
                            'Digital Marketing' => ['icon' => '📈', 'desc' => 'Drive traffic, manage ads, optimize search presence, and write copy.'],
                            'Data & AI' => ['icon' => '🤖', 'desc' => 'Build AI models, analyze large data sets, and extract key insights.'],
                            'Content & Business' => ['icon' => '📋', 'desc' => 'Write engaging content, construct business strategies, and perform market research.']
                        ];
                    ?>
                    <?php $__currentLoopData = $domainDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domName => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div data-domain="<?php echo e($domName); ?>" class="domain-card cursor-pointer p-4 bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:scale-105 hover:border-[var(--ig-accent)]/60 active:scale-95 transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="text-3xl mb-2 transition-transform duration-300 group-hover:scale-110"><?php echo e($info['icon']); ?></div>
                                <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1"><?php echo e($domName); ?></h4>
                                <p class="text-[11.5px] text-[var(--ig-muted)] leading-normal"><?php echo e($info['desc']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('primary_domain'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('primary_domain')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <!-- Role selection UI -->
            <div id="role-section-wrapper" class="space-y-3" style="display: none;">
                <label class="block text-sm font-semibold">Select Your Preferred Role</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="role-cards-container">
                    <!-- Populated dynamically via JS -->
                </div>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('preferred_role'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('preferred_role')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const careerSelection = document.getElementById('student-career-selection');
            const domainSelect = document.getElementById('primary_domain');
            const roleSelect = document.getElementById('preferred_role');
            const domainCards = document.querySelectorAll('.domain-card');
            const roleSectionWrapper = document.getElementById('role-section-wrapper');
            const roleCardsContainer = document.getElementById('role-cards-container');

            const domainsData = <?php echo json_encode(\App\Models\StudentProfile::$domains, 15, 512) ?>;
            const initialDomain = "<?php echo e(old('primary_domain')); ?>";
            const initialRole = "<?php echo e(old('preferred_role')); ?>";

            function toggleCareerSelection() {
                const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
                if (selectedRole === 'student') {
                    careerSelection.style.display = 'block';
                } else {
                    careerSelection.style.display = 'none';
                    clearDomainSelection();
                }
            }

            function clearDomainSelection() {
                domainSelect.value = '';
                roleSelect.value = '';
                roleSelect.disabled = true;
                roleSelect.innerHTML = '<option value="">Select Role (Select Domain first)</option>';
                
                domainCards.forEach(c => {
                    c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.25)]');
                    c.classList.add('border-gray-200');
                });
                roleSectionWrapper.style.display = 'none';
                roleCardsContainer.innerHTML = '';
            }

            function selectDomain(domainName) {
                domainSelect.value = domainName;
                domainSelect.dispatchEvent(new Event('change'));

                // Visual classes updates
                domainCards.forEach(c => {
                    if (c.getAttribute('data-domain') === domainName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.25)]');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.25)]');
                        c.classList.add('border-gray-200');
                    }
                });

                // Populate and show roles
                const roles = domainsData[domainName] || [];
                if (roles.length > 0) {
                    roleSelect.disabled = false;
                    let options = '<option value="">Select Role</option>';
                    roles.forEach(role => {
                        options += `<option value="${role}">${role}</option>`;
                    });
                    roleSelect.innerHTML = options;

                    // Render Role Cards
                    roleSectionWrapper.style.display = 'block';
                    roleCardsContainer.innerHTML = roles.map(role => `
                        <div data-role="${role}" class="role-card cursor-pointer p-3 bg-white border-2 border-gray-200 rounded-2xl text-center shadow-sm hover:shadow-md hover:scale-105 hover:border-[var(--ig-accent)]/60 active:scale-95 transition-all duration-300 group">
                            <p class="font-bold text-xs text-[var(--ig-ink-2)] group-hover:text-[var(--ig-ink)]">${role}</p>
                        </div>
                    `).join('');

                    // Click listeners for roles
                    roleCardsContainer.querySelectorAll('.role-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const roleName = this.getAttribute('data-role');
                            selectRole(roleName);
                        });
                    });
                } else {
                    roleSelect.disabled = true;
                    roleSelect.innerHTML = '<option value="">Select Role (Select Domain first)</option>';
                    roleSectionWrapper.style.display = 'none';
                    roleCardsContainer.innerHTML = '';
                }
            }

            function selectRole(roleName) {
                roleSelect.value = roleName;
                roleSelect.dispatchEvent(new Event('change'));

                roleCardsContainer.querySelectorAll('.role-card').forEach(c => {
                    if (c.getAttribute('data-role') === roleName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.2)]');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.2)]');
                        c.classList.add('border-gray-200');
                    }
                });
            }

            // Click listener for domain cards
            domainCards.forEach(card => {
                card.addEventListener('click', function() {
                    const dom = this.getAttribute('data-domain');
                    selectDomain(dom);
                });
            });

            roleInputs.forEach(input => input.addEventListener('change', toggleCareerSelection));

            toggleCareerSelection();

            // Populate initial state if redirected back with old input
            if (initialDomain) {
                selectDomain(initialDomain);
                if (initialRole) {
                    setTimeout(() => {
                        selectRole(initialRole);
                    }, 50);
                }
            }
        });
        </script>

        <div>
            <label for="password" class="block text-sm font-semibold mb-2">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   placeholder="At least 8 characters" class="ig-input" />
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold mb-2">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   placeholder="••••••••" class="ig-input" />
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password_confirmation'),'class' => 'mt-1.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password_confirmation')),'class' => 'mt-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" id="terms" required class="mt-1 rounded border-[var(--ig-line-2)] text-[var(--ig-ink)] w-4 h-4">
            <span class="text-[12px] text-[var(--ig-muted)]">By creating an account you agree to our <a href="<?php echo e(route('terms')); ?>" class="font-semibold text-[var(--ig-ink)] hover:text-[var(--ig-accent)]">Terms</a> and <a href="<?php echo e(route('privacy')); ?>" class="font-semibold text-[var(--ig-ink)] hover:text-[var(--ig-accent)]">Privacy Policy</a>.</span>
        </label>

        <button type="submit" class="ig-btn ig-btn-primary w-full justify-center">
            <span>Create account</span>
            <span class="arrow">→</span>
        </button>

        <div class="relative my-2">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[var(--ig-line)]"></div></div>
            <div class="relative flex justify-center"><span class="px-3 ig-mono text-[10px] text-[var(--ig-faint)] bg-[var(--ig-bg)] uppercase tracking-widest">or</span></div>
        </div>

        <a href="<?php echo e(route('auth.google')); ?>" class="ig-btn ig-btn-ghost w-full justify-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>Sign up with Google</span>
        </a>

        <p class="text-center text-sm text-[var(--ig-muted)] pt-2">
            Already have an account? <a href="<?php echo e(route('login')); ?>" class="font-semibold text-[var(--ig-ink)] hover:text-[var(--ig-accent)]">Sign in →</a>
        </p>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/auth/register.blade.php ENDPATH**/ ?>