<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Internship Workspace</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    {{ $offer->role ?: 'Software Engineer' }} Workspace
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5 uppercase tracking-wider">
                    Startup: <span class="font-bold text-[var(--ig-ink)]">{{ $offer->startup->company_name }}</span> | Status: {{ $offer->status }}
                </p>
            </div>
            <div>
                <a href="{{ route('student.internships.index') }}" class="ig-btn ig-btn-ghost text-xs">
                    <span>← Back to My Internships</span>
                </a>
            </div>
        </div>

        <!-- Session Flash Messages -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-8 text-sm">
                <p class="font-bold text-emerald-950">✓ {{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-900 font-bold shadow-sm">
                <p>⚠️ {{ session('error') }}</p>
            </div>
        @endif

        <div x-data="{ activeTab: 'overview', submitTaskId: null }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Navigation Sidebar -->
            <div class="lg:col-span-3">
                <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-x-visible pb-3 lg:pb-0" style="-ms-overflow-style:none;scrollbar-width:none;">
                    @php
                        $tabs = [
                            'overview'     => ['icon' => '📊', 'label' => 'Overview'],
                            'mission'      => ['icon' => '🎯', 'label' => 'Mission Board'],
                            'reports'      => ['icon' => '📝', 'label' => 'Weekly Reports'],
                            'messages'     => ['icon' => '💬', 'label' => 'Messages'],
                            'resources'    => ['icon' => '🗂️', 'label' => 'Resources'],
                            'certificates' => ['icon' => '🎖️', 'label' => 'Certificates'],
                        ];
                    @endphp
                    @foreach($tabs as $key => $tab)
                        <button @click="activeTab = '{{ $key }}'"
                                :class="activeTab === '{{ $key }}' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                                class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer border border-transparent" style="flex-shrink:0;">
                            {{ $tab['icon'] }} {{ $tab['label'] }}
                            @if($key === 'mission')
                                @php $pendingCount = $offer->internshipTasks->whereIn('status', ['pending', 'needs_changes'])->count(); @endphp
                                @if($pendingCount > 0)
                                    <span style="background:var(--ig-accent);color:white;font-size:9px;font-weight:800;padding:2px 7px;border-radius:999px;margin-left:4px;">{{ $pendingCount }}</span>
                                @endif
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- Badges Section -->
                @if($badges->count() > 0)
                    <div style="margin-top:24px;padding:16px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;" class="hidden lg:block">
                        <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--ig-muted);margin-bottom:8px;">Earned Badges</p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            @foreach($badges as $badge)
                                <span style="font-size:11px;font-weight:700;padding:4px 10px;background:var(--ig-surface);border:1px solid var(--ig-line);border-radius:10px;" title="Earned {{ $badge->earned_at->format('d M Y') }}">{{ $badge->badge_name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Content Panels -->
            <div class="lg:col-span-9" style="min-width:0;">
                <div class="ig-card p-6 md:p-8">

                {{-- ═══════════════════════════════════════
                     TAB: OVERVIEW
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'overview'" class="space-y-8">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Workspace Overview</h2>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $stats = [
                                ['label' => 'Current Streak', 'value' => '🔥 ' . ($offer->current_streak ?? 0) . ' days', 'color' => '#f97316'],
                                ['label' => 'Highest Streak', 'value' => '🏆 ' . ($offer->highest_streak ?? 0) . ' days', 'color' => '#8b5cf6'],
                                ['label' => 'Approved Tasks', 'value' => '✅ ' . $offer->approved_tasks_count, 'color' => '#059669'],
                                ['label' => 'Performance', 'value' => '⭐ ' . $offer->performance_score . '%', 'color' => '#0ea5e9'],
                            ];
                        @endphp
                        @foreach($stats as $stat)
                            <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                                <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--ig-muted);">{{ $stat['label'] }}</p>
                                <p style="font-size:20px;font-weight:900;color:{{ $stat['color'] }};margin-top:4px;">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-[var(--ig-muted)]">Contract Progress</span>
                            <span class="text-[var(--ig-accent)] font-bold">{{ $offer->progress_pct }}%</span>
                        </div>
                        <div style="width:100%;background:var(--ig-bg-2);border-radius:999px;height:12px;">
                            <div style="width:{{ $offer->progress_pct }}%;background:linear-gradient(to right,var(--ig-accent-soft),var(--ig-accent));height:12px;border-radius:999px;transition:width .5s;"></div>
                        </div>
                    </div>

                    <!-- Streak Freeze Status -->
                    <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;display:flex;align-items:center;gap:12px;">
                        <span style="font-size:24px;">🎁</span>
                        <div>
                            <p style="font-size:12px;font-weight:700;color:var(--ig-ink);">Monthly Streak Freeze</p>
                            @if($offer->streak_freeze_used_at && $offer->streak_freeze_used_at->isCurrentMonth())
                                <p style="font-size:11px;color:#ef4444;font-weight:600;">Used on {{ $offer->streak_freeze_used_at->format('d M') }} — resets next month</p>
                            @else
                                <p style="font-size:11px;color:#059669;font-weight:600;">✅ Available — auto-applies if you miss a submission window</p>
                            @endif
                        </div>
                    </div>

                    <!-- Meta Details -->
                    <div class="grid grid-cols-3 gap-6" style="font-size:12px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:12px;padding:16px;">
                        <div>
                            <p style="font-size:9px;text-transform:uppercase;letter-spacing:0.05em;color:var(--ig-muted);font-weight:700;">Stipend</p>
                            <p style="color:var(--ig-ink);font-weight:800;margin-top:2px;">₹{{ number_format($offer->compensation, 0) }} / {{ $offer->compensation_period }}</p>
                        </div>
                        <div>
                            <p style="font-size:9px;text-transform:uppercase;letter-spacing:0.05em;color:var(--ig-muted);font-weight:700;">Start Date</p>
                            <p style="color:var(--ig-ink);font-weight:800;margin-top:2px;">{{ $offer->start_date ? $offer->start_date->format('d M, Y') : '—' }}</p>
                        </div>
                        <div>
                            <p style="font-size:9px;text-transform:uppercase;letter-spacing:0.05em;color:var(--ig-muted);font-weight:700;">End Date</p>
                            <p style="color:var(--ig-ink);font-weight:800;margin-top:2px;">{{ $offer->end_date ? $offer->end_date->format('d M, Y') : 'Ongoing' }}</p>
                        </div>
                    </div>

                    <!-- Pending Reviews Alert -->
                    @if($offer->pending_review_count > 0)
                        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:14px;padding:16px;display:flex;align-items:center;gap:12px;">
                            <span style="font-size:24px;">⏳</span>
                            <div>
                                <p style="font-size:13px;font-weight:700;color:#92400e;">{{ $offer->pending_review_count }} task(s) waiting for startup review</p>
                                <p style="font-size:11px;color:#a16207;">Your submissions are under review. Streaks increase only when tasks are approved.</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ═══════════════════════════════════════
                     TAB: MISSION BOARD
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'mission'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-2">Mission Board</h2>
                    <p style="font-size:12px;color:var(--ig-muted);margin-bottom:16px;">Complete tasks assigned by your startup. Approved submissions increase your streak and IPRS score.</p>

                    @forelse($milestones as $milestoneName => $tasks)
                        <div style="margin-bottom:24px;">
                            <h3 style="font-size:14px;font-weight:800;color:var(--ig-ink);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:var(--ig-accent);display:inline-block;"></span>
                                {{ $milestoneName }}
                                <span style="font-size:10px;font-weight:600;color:var(--ig-muted);">({{ $tasks->where('status','approved')->count() }}/{{ $tasks->count() }})</span>
                            </h3>

                            <div class="space-y-3">
                                @foreach($tasks as $task)
                                    @php
                                        $statusConfig = [
                                            'pending'       => ['icon' => '🔴', 'label' => 'Pending',           'bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#991b1b'],
                                            'needs_changes' => ['icon' => '🟡', 'label' => 'Needs Changes',     'bg' => '#fefce8', 'border' => '#fde68a', 'text' => '#92400e'],
                                            'submitted'     => ['icon' => '🔵', 'label' => 'Awaiting Approval', 'bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e40af'],
                                            'approved'      => ['icon' => '✅', 'label' => 'Approved',          'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#166534'],
                                        ];
                                        $sc = $statusConfig[$task->status] ?? $statusConfig['pending'];
                                        $priorityColors = ['low' => '#6b7280', 'medium' => '#f59e0b', 'high' => '#ef4444'];
                                    @endphp

                                    <div style="background:var(--ig-surface);border:1px solid var(--ig-line);border-radius:14px;padding:16px;transition:border-color .2s;" class="hover:border-[var(--ig-line-2)]">
                                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
                                            <div style="flex:1;min-width:200px;">
                                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                                    <span>{{ $sc['icon'] }}</span>
                                                    <h4 style="font-size:14px;font-weight:700;color:var(--ig-ink);">{{ $task->title }}</h4>
                                                </div>
                                                @if($task->description)
                                                    <p style="font-size:12px;color:var(--ig-ink-2);margin-top:4px;line-height:1.5;">{{ Str::limit($task->description, 200) }}</p>
                                                @endif
                                                <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
                                                    <span style="font-size:9px;font-weight:700;text-transform:uppercase;padding:3px 8px;border-radius:6px;background:{{ $sc['bg'] }};color:{{ $sc['text'] }};border:1px solid {{ $sc['border'] }};">{{ $sc['label'] }}</span>
                                                    <span style="font-size:9px;font-weight:700;text-transform:uppercase;padding:3px 8px;border-radius:6px;color:{{ $priorityColors[$task->priority] ?? '#6b7280' }};border:1px solid currentColor;">{{ $task->priority }} priority</span>
                                                    @if($task->due_date)
                                                        <span style="font-size:9px;font-weight:600;padding:3px 8px;border-radius:6px;color:var(--ig-muted);border:1px solid var(--ig-line);">Due: {{ $task->due_date->format('d M') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <div style="flex-shrink:0;">
                                                @if(in_array($task->status, ['pending', 'needs_changes']))
                                                    <button @click="submitTaskId = submitTaskId === {{ $task->id }} ? null : {{ $task->id }}"
                                                            style="background:var(--ig-accent);color:white;font-weight:700;font-size:11px;padding:8px 16px;border-radius:10px;border:none;cursor:pointer;transition:opacity .2s;">
                                                        📤 Submit Work
                                                    </button>
                                                @elseif($task->status === 'submitted')
                                                    <span style="font-size:11px;font-weight:700;color:#1e40af;padding:8px 16px;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe;">⏳ Under Review</span>
                                                @else
                                                    <span style="font-size:11px;font-weight:700;color:#166534;padding:8px 16px;background:#f0fdf4;border-radius:10px;border:1px solid #bbf7d0;">✅ Done</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Feedback from startup (if needs_changes) -->
                                        @if($task->status === 'needs_changes' && $task->latestSubmission && $task->latestSubmission->startup_feedback)
                                            <div style="margin-top:12px;padding:12px;background:#fefce8;border:1px solid #fde68a;border-radius:10px;font-size:12px;color:#92400e;">
                                                <strong style="display:block;font-size:9px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:4px;color:#a16207;">💬 Startup Feedback</strong>
                                                {{ $task->latestSubmission->startup_feedback }}
                                            </div>
                                        @endif

                                        <!-- Submit Work Form (inline, toggled) -->
                                        <div x-show="submitTaskId === {{ $task->id }}" x-cloak style="margin-top:16px;padding:16px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:12px;">
                                            <form method="POST" action="{{ route('student.internships.tasks.submit', ['offer' => $offer->id, 'task' => $task->id]) }}" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Work Description *</label>
                                                    <textarea name="description" required placeholder="Describe what you completed, how you approached it..." rows="3"
                                                              style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div>
                                                        <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">GitHub URL</label>
                                                        <input type="url" name="github_url" placeholder="https://github.com/..."
                                                               style="width:100%;padding:8px 12px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;color:var(--ig-ink);box-sizing:border-box;">
                                                    </div>
                                                    <div>
                                                        <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Pull Request URL</label>
                                                        <input type="url" name="pull_request_url" placeholder="https://github.com/.../pull/..."
                                                               style="width:100%;padding:8px 12px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;color:var(--ig-ink);box-sizing:border-box;">
                                                    </div>
                                                    <div>
                                                        <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Demo URL</label>
                                                        <input type="url" name="demo_url" placeholder="https://..."
                                                               style="width:100%;padding:8px 12px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;color:var(--ig-ink);box-sizing:border-box;">
                                                    </div>
                                                </div>
                                                <div style="display:flex;gap:8px;padding-top:4px;">
                                                    <button type="submit" style="background:#059669;color:white;font-weight:800;font-size:12px;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;">
                                                        🚀 Submit for Review
                                                    </button>
                                                    <button type="button" @click="submitTaskId = null" style="background:transparent;color:var(--ig-muted);font-weight:600;font-size:12px;padding:10px 16px;border-radius:12px;border:1px solid var(--ig-line);cursor:pointer;">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:40px 20px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;">
                            <span style="font-size:36px;">📋</span>
                            <p style="font-size:14px;font-weight:700;color:var(--ig-ink);margin-top:12px;">No tasks assigned yet</p>
                            <p style="font-size:12px;color:var(--ig-muted);margin-top:4px;">Your startup will assign tasks and milestones here. Check back soon!</p>
                        </div>
                    @endforelse
                </div>

                {{-- ═══════════════════════════════════════
                     TAB: WEEKLY REPORTS
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'reports'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Weekly Progress Reports</h2>

                    <!-- Report Submit Form -->
                    <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;padding:20px;">
                        <h4 style="font-weight:700;font-size:16px;color:var(--ig-ink);margin-bottom:12px;">Submit Weekly Report</h4>
                        <form method="POST" action="{{ route('student.internship.reports.store', $offer->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Week Number</label>
                                <input type="number" name="week_number" required value="{{ $nextWeekNumber }}" min="1"
                                       style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;font-weight:600;color:var(--ig-ink);box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tasks Completed</label>
                                <textarea name="tasks_completed" required placeholder="Summarize what you achieved this week..." rows="3"
                                          style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Challenges</label>
                                <textarea name="challenges" required placeholder="What blocked you..." rows="2"
                                          style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Next Week Goals</label>
                                <textarea name="next_week_goals" required placeholder="Planned deliverables..." rows="2"
                                          style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                            </div>
                            <button type="submit" style="background:#059669;color:white;font-weight:800;font-size:12px;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;">
                                Submit Week Report
                            </button>
                        </form>
                    </div>

                    <!-- Report History -->
                    <div class="space-y-4">
                        @forelse($offer->weeklyReports as $report)
                            <div style="border:1px solid var(--ig-line);border-radius:16px;padding:16px;background:var(--ig-bg-2);">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                                    <span class="ig-chip ig-chip-accent" style="font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;">Week {{ $report->week_number }}</span>
                                    @if($report->rating)
                                        <span style="font-size:12px;font-weight:700;color:#047857;">⭐ {{ $report->rating }}/5</span>
                                    @else
                                        <span style="font-size:12px;color:var(--ig-muted);">⏳ Pending Review</span>
                                    @endif
                                </div>
                                <p style="font-size:12px;color:var(--ig-ink);"><strong>Done:</strong> {{ $report->tasks_completed }}</p>
                                @if($report->startup_feedback)
                                    <div style="margin-top:12px;padding:12px;background:var(--ig-surface);border:1px solid var(--ig-line);border-radius:12px;font-size:12px;color:#475569;font-style:italic;">
                                        <strong style="color:#64748b;font-weight:700;display:block;font-style:normal;text-transform:uppercase;letter-spacing:0.1em;font-size:8px;margin-bottom:4px;">— Startup Review</strong>
                                        "{{ $report->startup_feedback }}"
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p style="font-size:12px;color:var(--ig-muted);padding:16px 0;text-align:center;">No weekly reports submitted yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- ═══════════════════════════════════════
                     TAB: MESSAGES
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'messages'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Communication</h2>
                    <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;padding:24px;text-align:center;">
                        <p style="font-size:14px;font-weight:600;color:var(--ig-ink);margin-bottom:16px;">Chat with {{ $offer->startup->company_name }} team</p>
                        @if($conversationId)
                            <a href="{{ route('messages.show', $conversationId) }}" class="ig-btn ig-btn-primary" style="font-size:12px;padding:10px 24px;border-radius:12px;display:inline-block;">
                                💬 Open Messages
                            </a>
                        @else
                            <a href="{{ route('messages.create', ['studentId' => $offer->student_profile_id, 'startupId' => $offer->startup_profile_id, 'taskId' => $offer->source_task_id ?? '']) }}" class="ig-btn ig-btn-primary" style="font-size:12px;padding:10px 24px;border-radius:12px;display:inline-block;">
                                💬 Start Conversation
                            </a>
                        @endif
                    </div>
                </div>

                {{-- ═══════════════════════════════════════
                     TAB: RESOURCES
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'resources'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Resources & Documentation</h2>
                    @php
                        $typeIcons = ['link' => '🔗', 'document' => '📄', 'figma' => '🎨', 'github' => '🐙', 'api' => '⚡'];
                    @endphp
                    @forelse($offer->resources as $resource)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--ig-line);">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="font-size:18px;">{{ $typeIcons[$resource->type] ?? '🔗' }}</span>
                                <div>
                                    <p style="font-size:13px;font-weight:700;color:var(--ig-ink);">{{ $resource->title }}</p>
                                    <p style="font-size:10px;color:var(--ig-muted);text-transform:uppercase;">{{ $resource->type }}</p>
                                </div>
                            </div>
                            <a href="{{ $resource->url }}" target="_blank" style="font-size:11px;font-weight:700;color:var(--ig-accent);text-decoration:none;">Open →</a>
                        </div>
                    @empty
                        <p style="font-size:12px;color:var(--ig-muted);padding:16px 0;text-align:center;">No resources shared yet. Your startup will add documentation, repos, and links here.</p>
                    @endforelse
                </div>

                {{-- ═══════════════════════════════════════
                     TAB: CERTIFICATES
                ═══════════════════════════════════════ --}}
                <div x-show="activeTab === 'certificates'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Certificates</h2>
                    @php
                        $certificate = \App\Models\Certificate::where('hiring_offer_id', $offer->id)->first();
                    @endphp
                    @if($certificate)
                        <div style="background:rgba(238,242,255,0.3);border:1px solid #c7d2fe;border-radius:16px;padding:24px;text-align:center;">
                            <span style="font-size:2.5rem;">🎖️</span>
                            <h4 style="font-weight:700;font-size:18px;color:#1e1b4b;margin-top:8px;">Experience Certificate Issued</h4>
                            <p style="font-size:12px;color:rgba(55,48,163,0.8);margin-top:4px;">Certificate: {{ $certificate->certificate_number }}</p>
                            <p style="font-size:12px;color:rgba(55,48,163,0.8);">Issued: {{ $certificate->issued_at->format('d M, Y') }}</p>
                            <div style="margin-top:12px;font-size:11px;color:var(--ig-ink-2);">
                                Performance: {{ $offer->performance_score }}% · Approved Tasks: {{ $offer->approved_tasks_count }} · Highest Streak: {{ $offer->highest_streak ?? 0 }} days
                            </div>
                            <a href="{{ route('certificates.verify', $certificate->certificate_number) }}" target="_blank"
                               style="display:inline-block;margin-top:16px;background:#4f46e5;color:white;font-weight:800;font-size:12px;padding:8px 24px;border-radius:12px;text-decoration:none;">
                                View Credentials
                            </a>
                        </div>
                    @else
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;padding:24px;text-align:center;">
                            <p style="font-size:14px;font-weight:600;color:var(--ig-ink);margin-bottom:4px;">No certificate issued yet.</p>
                            <p style="font-size:12px;color:var(--ig-muted);">Your certificate will include your performance score, approved task count, and streak history.</p>
                        </div>
                    @endif
                </div>

                </div>{{-- end .ig-card --}}
            </div>{{-- end lg:col-span-9 --}}
        </div>{{-- end grid --}}
    </div>
</x-app-layout>
