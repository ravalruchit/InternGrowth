<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <?php if(auth()->check() && auth()->user()->isStudent()): ?>
            <?php if (isset($component)) { $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.reputation-card','data' => ['compact' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('reputation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $attributes = $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $component = $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?>
        <?php endif; ?>

        <!-- Main Chat Panel -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col h-[600px]">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="<?php echo e(route('messages.index')); ?>" class="text-white hover:text-indigo-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-xl font-black text-white tracking-tight">
                                <?php echo e(auth()->user()->isStudent() ? $conversation->startup->company_name : $conversation->student->user->name); ?>

                            </h2>
                            <?php if($conversation->task): ?>
                                <p class="text-indigo-100 text-xs font-semibold">Project Ref: <?php echo e($conversation->task->title); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if(auth()->user()->isStartup()): ?>
                        <button onclick="openScheduleModal()" class="bg-white/20 hover:bg-white/30 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs tracking-wide transition flex items-center space-x-1 border border-white/10 shadow-sm">
                            <span>📅 Schedule Interview</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                <?php $__empty_1 = true; $__currentLoopData = $conversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex <?php echo e($message->sender_id === auth()->id() ? 'justify-end' : 'justify-start'); ?> mb-4">
                        <div class="w-full max-w-md">
                            <?php if($message->type === 'interview' && $message->interview): ?>
                                <?php
                                    $interview = $message->interview;
                                    $statusColors = match($interview->status) {
                                        'pending' => 'border-yellow-250 bg-yellow-50/80 shadow-yellow-50/20',
                                        'accepted' => 'border-green-250 bg-green-50/80 shadow-green-50/20',
                                        'completed' => 'border-indigo-250 bg-indigo-50/80 shadow-indigo-50/20',
                                        'rejected' => 'border-red-250 bg-red-50/80 shadow-red-50/20',
                                        'cancelled' => 'border-gray-250 bg-gray-50/80 shadow-gray-50/20',
                                        'no_show' => 'border-orange-250 bg-orange-50/80 shadow-orange-50/20',
                                        default => 'border-slate-250 bg-slate-50/80',
                                    };
                                    $badgeStyle = match($interview->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        'accepted' => 'bg-green-100 text-green-800 border border-green-200',
                                        'completed' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                        'rejected' => 'bg-red-100 text-red-800 border border-red-200',
                                        'cancelled' => 'bg-gray-100 text-gray-850 border border-gray-250',
                                        'no_show' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                        default => 'bg-slate-100 text-slate-800 border border-slate-200',
                                    };
                                ?>
                                <div class="border <?php echo e($statusColors); ?> rounded-3xl p-5 shadow-md space-y-4 text-gray-900">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?php echo e($badgeStyle); ?> mb-1">
                                                <?php echo e(ucfirst($interview->status)); ?>

                                            </span>
                                            <h4 class="text-base font-extrabold text-gray-950"><?php echo e($interview->title); ?></h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold text-indigo-750 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                            <?php echo e($interview->duration_minutes); ?> min
                                        </span>
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-2 text-xs text-gray-650">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm">📅</span>
                                            <span class="font-bold text-gray-800">
                                                <?php echo e($interview->scheduled_at->format('M d, Y \a\t g:i A')); ?>

                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm">📍</span>
                                            <?php if($interview->type === 'online'): ?>
                                                <a href="<?php echo e(Str::startsWith($interview->location, 'http') ? $interview->location : 'https://' . $interview->location); ?>" target="_blank" class="text-indigo-650 hover:underline font-bold flex items-center space-x-0.5">
                                                    <span>Join Online Session</span>
                                                    <span>↗</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="font-semibold text-gray-800"><?php echo e($interview->location); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($interview->agenda): ?>
                                            <div class="bg-white border border-gray-150 rounded-xl p-3 mt-2">
                                                <p class="font-bold text-gray-750 text-[10px] uppercase tracking-wider mb-0.5">Agenda & Prep Instructions:</p>
                                                <p class="text-xs leading-relaxed text-gray-600"><?php echo e($interview->agenda); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Actions / Evaluation Breakdown -->
                                    <div class="pt-3 border-t border-dashed border-gray-250/80">
                                        <?php if($interview->status === 'completed'): ?>
                                            <!-- Completed Stats & Feedback -->
                                            <div class="space-y-3 text-xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-500 font-bold uppercase tracking-wider text-[10px]">Interview Outcome:</span>
                                                    <?php
                                                        $friendlyOutcome = match($interview->outcome) {
                                                            'proceed_to_offer' => 'Proceed to Offer',
                                                            'keep_in_pipeline' => 'Keep in Pipeline',
                                                            'needs_another_round' => 'Needs Another Round',
                                                            'rejected' => auth()->user()->isStudent() ? 'Needs Improvement' : 'Rejected',
                                                            default => ucwords(str_replace('_', ' ', $interview->outcome)),
                                                        };
                                                        $outcomeBadge = match($interview->outcome) {
                                                            'proceed_to_offer' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                                            'keep_in_pipeline' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                                            'needs_another_round' => 'bg-yellow-100 text-yellow-850 border border-yellow-200',
                                                            'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                                            default => 'bg-slate-100 text-slate-800 border border-slate-200',
                                                        };
                                                    ?>
                                                    <span class="font-extrabold px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-widest <?php echo e($outcomeBadge); ?>">
                                                        <?php echo e($friendlyOutcome); ?>

                                                    </span>
                                                </div>

                                                <?php if(auth()->user()->isStartup() || auth()->user()->isAdmin()): ?>
                                                    <!-- Raw Scores (Startup/Admin Only) -->
                                                    <div class="bg-white border border-gray-200 rounded-xl p-3.5 space-y-1.5 shadow-inner">
                                                        <div class="flex justify-between items-center pb-1.5 border-b border-gray-100 mb-1.5">
                                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Internal Skill Rating</span>
                                                            <span class="text-[9px] text-indigo-600 bg-indigo-50 font-bold px-1.5 py-0.5 rounded">Startup & Admin Only</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Technical Competency:</span>
                                                            <span class="font-extrabold text-gray-950"><?php echo e($interview->technical_rating); ?>/10</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Communication Quality:</span>
                                                            <span class="font-extrabold text-gray-950"><?php echo e($interview->communication_rating); ?>/10</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Problem Solving Skills:</span>
                                                            <span class="font-extrabold text-gray-950"><?php echo e($interview->problem_solving_rating); ?>/10</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($interview->feedback_notes): ?>
                                                    <div class="bg-indigo-50/50 border border-indigo-100/60 rounded-xl p-3.5 text-indigo-950">
                                                        <p class="font-bold text-[10px] uppercase tracking-wider text-indigo-800 mb-1">Feedback Notes:</p>
                                                        <p class="text-xs leading-relaxed font-medium text-slate-800"><?php echo e($interview->feedback_notes); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif($interview->status === 'pending'): ?>
                                            <?php if(auth()->user()->isStudent()): ?>
                                                <div class="flex items-center space-x-2">
                                                    <form method="POST" action="<?php echo e(route('student.interviews.accept', $interview->id)); ?>" class="flex-1">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2 rounded-xl text-xs transition shadow-sm">
                                                            Accept Invitation
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?php echo e(route('student.interviews.reject', $interview->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-xl text-xs transition border border-gray-250 shadow-sm">
                                                            Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex justify-between items-center text-xs text-gray-550">
                                                    <span>Waiting for student confirmation</span>
                                                    <form method="POST" action="<?php echo e(route('startup.interviews.cancel', $interview->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="text-red-500 hover:text-red-700 font-extrabold text-xs">
                                                            Cancel Invitation
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif($interview->status === 'accepted'): ?>
                                            <?php if(auth()->user()->isStartup()): ?>
                                                <div class="space-y-2">
                                                    <button type="button" onclick="openCompleteModal(<?php echo e($interview->id); ?>)" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg text-white font-black py-2.5 px-4 rounded-xl text-xs transition">
                                                        Complete & Evaluate Candidate
                                                    </button>
                                                    <div class="flex items-center justify-between text-xs pt-1">
                                                        <form method="POST" action="<?php echo e(route('startup.interviews.noshow', $interview->id)); ?>" class="flex-1 mr-2">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold py-1.5 rounded-lg transition border border-amber-250">
                                                                Candidate No Show ⚠️
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="<?php echo e(route('startup.interviews.cancel', $interview->id)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="text-gray-500 hover:text-red-600 font-semibold py-1.5 px-2">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-xs text-green-700 font-bold flex items-center space-x-1">
                                                    <span>✓ Invitation accepted. Ready for interview.</span>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif($interview->status === 'no_show'): ?>
                                            <div class="text-xs text-orange-800 bg-orange-50 border border-orange-200 rounded-lg p-2.5 font-semibold">
                                                ⚠️ Candidate marked as No-Show. This has penalty on their performance score.
                                            </div>
                                        <?php else: ?>
                                            <div class="text-xs text-gray-500 font-medium italic">
                                                Interview is <?php echo e($interview->status); ?>.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Standard text message layout -->
                                <div class="<?php echo e($message->sender_id === auth()->id() ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-indigo-100' : 'bg-white text-gray-900 border border-gray-150'); ?> rounded-2xl px-4 py-3 shadow-sm">
                                    <p class="text-xs font-bold mb-1 <?php echo e($message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500'); ?>"><?php echo e($message->sender->name); ?></p>
                                    <p class="text-sm leading-relaxed"><?php echo e($message->message); ?></p>
                                </div>
                            <?php endif; ?>
                            <p class="text-[10px] text-gray-400 mt-1 <?php echo e($message->sender_id === auth()->id() ? 'text-right' : 'text-left'); ?>">
                                <?php echo e($message->created_at->format('M d, g:i A')); ?>

                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-20">
                        <span class="text-4xl block mb-2">💬</span>
                        <p class="text-gray-500 font-bold">No messages yet.</p>
                        <p class="text-gray-400 text-xs mt-1">Start the conversation by typing below!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-150 p-4 bg-white flex-shrink-0">
                <form method="POST" action="<?php echo e(route('messages.store', $conversation->id)); ?>" class="flex space-x-3">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="message" placeholder="Type your message..." required autocomplete="off"
                        class="flex-1 border-gray-250 rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-7 py-3 rounded-full font-black text-sm hover:shadow-lg transition">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedule Interview Modal (Startup Only) -->
    <?php if(auth()->user()->isStartup()): ?>
        <div id="schedule-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
            <div class="bg-white/95 backdrop-blur-lg border border-purple-100 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
                <button onclick="closeScheduleModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
                    <span>📅 Schedule Interview</span>
                </h3>
                <p class="text-xs text-gray-500 mb-6">Send an interview invitation to <?php echo e($conversation->student->user->name); ?>.</p>
                
                <form method="POST" action="<?php echo e(route('startup.interviews.store', $conversation->id)); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Title</label>
                        <input type="text" name="title" required placeholder="e.g. Technical Coding Round"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Scheduled At</label>
                            <input type="datetime-local" name="scheduled_at" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Duration (min)</label>
                            <select name="duration_minutes" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <option value="15">15 Minutes</option>
                                <option value="30" selected>30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60">65 Minutes</option>
                                <option value="90">90 Minutes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Type</label>
                            <select name="type" required onchange="updateLocationPlaceholder(this.value)"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <option value="online" selected>Google Meet / Zoom</option>
                                <option value="phone">Phone call</option>
                                <option value="in_person">In Person / Address</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Location / Contact</label>
                            <input type="text" name="location" id="location-input" required placeholder="Google Meet link or URL"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agenda & Prep Notes</label>
                        <textarea name="agenda" rows="3" placeholder="Explain agenda, topics, coding workspace needed..."
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                            Schedule & Send
                        </button>
                        <button type="button" onclick="closeScheduleModal()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Evaluation / Complete Modal (Startup Only) -->
        <div id="complete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
            <div class="bg-white/95 backdrop-blur-lg border border-purple-100 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
                <button onclick="closeCompleteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-655 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
                    <span>✅ Log Interview Evaluation</span>
                </h3>
                <p class="text-xs text-gray-500 mb-6">Evaluate the student's performance. Detailed ratings (1-10) will be kept private from the student.</p>
                
                <form id="complete-form" method="POST" action="" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hiring Outcome Decision</label>
                        <select name="outcome" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <option value="proceed_to_offer">Proceed to Offer</option>
                            <option value="keep_in_pipeline">Keep in Pipeline</option>
                            <option value="needs_another_round">Needs Another Round</option>
                            <option value="rejected">Rejected (Student view says: "Needs Improvement")</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Technical (1-10)</label>
                            <input type="number" name="technical_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Communication (1-10)</label>
                            <input type="number" name="communication_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Problem Solving (1-10)</label>
                            <input type="number" name="problem_solving_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Feedback & Key Learnings (Shown to student)</label>
                        <textarea name="feedback_notes" rows="4" required placeholder="E.g. Work on API design and database optimization..."
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                            Submit Evaluation
                        </button>
                        <button type="button" onclick="closeCompleteModal()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function openScheduleModal() {
            const modal = document.getElementById('schedule-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }
        function closeScheduleModal() {
            const modal = document.getElementById('schedule-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
        function updateLocationPlaceholder(val) {
            const input = document.getElementById('location-input');
            if (!input) return;
            if (val === 'online') {
                input.placeholder = 'Google Meet link or URL';
            } else if (val === 'phone') {
                input.placeholder = 'e.g. +91 98765 43210';
            } else {
                input.placeholder = 'e.g. Office Address Suite 4B';
            }
        }

        function openCompleteModal(interviewId) {
            const modal = document.getElementById('complete-modal');
            const form = document.getElementById('complete-form');
            if (modal && form) {
                form.action = `/startup/interviews/${interviewId}/complete`;
                modal.classList.remove('hidden');
            }
        }
        function closeCompleteModal() {
            const modal = document.getElementById('complete-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/messages/show.blade.php ENDPATH**/ ?>