<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Team Management</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    {{ $offer->student->user->name ?? 'Intern' }} — Workspace
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5 uppercase tracking-wider">
                    Role: <span class="font-bold text-[var(--ig-ink)]">{{ $offer->role ?: 'Intern' }}</span> |
                    🔥 Streak: {{ $offer->current_streak ?? 0 }} |
                    ⭐ Performance: {{ $offer->performance_score }}%
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('startup.team.index') }}" class="ig-btn ig-btn-ghost text-xs">← Back to Team</a>
            </div>
        </div>

        <!-- Flash -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-8 text-sm"><p class="font-bold text-emerald-950">✓ {{ session('success') }}</p></div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-900 font-bold shadow-sm"><p>⚠️ {{ session('error') }}</p></div>
        @endif

        <div x-data="{ activeTab: 'tasks', showCreateForm: false }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Sidebar -->
            <div class="lg:col-span-3">
                <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-x-visible pb-3 lg:pb-0" style="-ms-overflow-style:none;scrollbar-width:none;">
                    <button @click="activeTab = 'tasks'"
                            :class="activeTab === 'tasks' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                            class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer" style="flex-shrink:0;">
                        🎯 Mission Board
                        @if($pendingReviewCount > 0)
                            <span style="background:#ef4444;color:white;font-size:9px;font-weight:800;padding:2px 7px;border-radius:999px;">{{ $pendingReviewCount }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'review'"
                            :class="activeTab === 'review' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                            class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer" style="flex-shrink:0;">
                        📋 Review Center
                    </button>
                    <button @click="activeTab = 'reports'"
                            :class="activeTab === 'reports' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                            class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer" style="flex-shrink:0;">
                        📝 Weekly Reports
                    </button>
                    <button @click="activeTab = 'resources'"
                            :class="activeTab === 'resources' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                            class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer" style="flex-shrink:0;">
                        🗂️ Resources
                    </button>
                    <button @click="activeTab = 'analytics'"
                            :class="activeTab === 'analytics' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-[var(--ig-ink-2)] hover:bg-[var(--ig-bg-2)]'"
                            class="px-4 py-2.5 lg:py-3 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 transition cursor-pointer" style="flex-shrink:0;">
                        📊 Analytics
                    </button>
                </div>

                <!-- Quick Stats -->
                <div style="margin-top:24px;padding:16px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;" class="hidden lg:block">
                    <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--ig-muted);margin-bottom:10px;">Quick Stats</p>
                    <div class="space-y-2" style="font-size:12px;">
                        <div style="display:flex;justify-content:space-between;"><span style="color:var(--ig-muted);">Total Tasks</span><span style="font-weight:800;color:var(--ig-ink);">{{ $tasks->count() }}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:var(--ig-muted);">Approved</span><span style="font-weight:800;color:#059669;">{{ $tasks->where('status','approved')->count() }}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:var(--ig-muted);">Pending Review</span><span style="font-weight:800;color:#f59e0b;">{{ $pendingReviewCount }}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:var(--ig-muted);">Streak</span><span style="font-weight:800;color:#f97316;">🔥 {{ $offer->current_streak ?? 0 }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-9" style="min-width:0;">
                <div class="ig-card p-6 md:p-8">

                {{-- ═══ TAB: MISSION BOARD (Create & Manage Tasks) ═══ --}}
                <div x-show="activeTab === 'tasks'" class="space-y-6">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Mission Board</h2>
                        <button @click="showCreateForm = !showCreateForm"
                                style="background:var(--ig-accent);color:white;font-weight:700;font-size:12px;padding:8px 20px;border-radius:10px;border:none;cursor:pointer;">
                            + Assign New Task
                        </button>
                    </div>

                    <!-- Create Task Form -->
                    <div x-show="showCreateForm" x-cloak style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;padding:20px;">
                        <h4 style="font-weight:700;font-size:14px;color:var(--ig-ink);margin-bottom:12px;">Create New Task</h4>
                        <form method="POST" action="{{ route('startup.team.tasks.store', $offer->id) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Task Title *</label>
                                <input type="text" name="title" required placeholder="e.g. Build Authentication API"
                                       style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;font-weight:600;color:var(--ig-ink);box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Description</label>
                                <textarea name="description" placeholder="Describe expected deliverables..." rows="3"
                                          style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Priority *</label>
                                    <select name="priority" required
                                            style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);box-sizing:border-box;">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Milestone</label>
                                    <input type="text" name="milestone_name" placeholder="e.g. Week 1 – Foundation"
                                           style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);box-sizing:border-box;">
                                </div>
                                <div>
                                    <label style="display:block;font-size:10px;font-weight:700;color:var(--ig-ink-2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Due Date</label>
                                    <input type="date" name="due_date"
                                           style="width:100%;padding:10px 14px;background:var(--ig-surface);border:1px solid var(--ig-line-2);border-radius:12px;font-size:13px;color:var(--ig-ink);box-sizing:border-box;">
                                </div>
                            </div>
                            <button type="submit" style="background:#059669;color:white;font-weight:800;font-size:12px;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;">
                                ✅ Assign Task
                            </button>
                        </form>
                    </div>

                    <!-- Task List by Milestone -->
                    @forelse($milestones as $milestoneName => $milestoneTasks)
                        <div style="margin-bottom:20px;">
                            <h3 style="font-size:14px;font-weight:800;color:var(--ig-ink);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:var(--ig-accent);display:inline-block;"></span>
                                {{ $milestoneName }}
                                <span style="font-size:10px;color:var(--ig-muted);">({{ $milestoneTasks->where('status','approved')->count() }}/{{ $milestoneTasks->count() }})</span>
                            </h3>
                            <div class="space-y-3">
                                @foreach($milestoneTasks as $task)
                                    @php
                                        $sc = ['pending' => ['🔴','Pending','#fef2f2','#991b1b'], 'needs_changes' => ['🟡','Changes Sent','#fefce8','#92400e'], 'submitted' => ['🔵','Submitted','#eff6ff','#1e40af'], 'approved' => ['✅','Approved','#f0fdf4','#166534']];
                                        $s = $sc[$task->status] ?? $sc['pending'];
                                    @endphp
                                    <div style="background:var(--ig-surface);border:1px solid var(--ig-line);border-radius:14px;padding:14px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;">
                                            <div>
                                                <span>{{ $s[0] }}</span>
                                                <strong style="font-size:13px;color:var(--ig-ink);margin-left:4px;">{{ $task->title }}</strong>
                                                <span style="font-size:9px;font-weight:700;text-transform:uppercase;padding:2px 6px;border-radius:5px;background:{{ $s[2] }};color:{{ $s[3] }};margin-left:6px;">{{ $s[1] }}</span>
                                                @if($task->due_date)
                                                    <span style="font-size:9px;color:var(--ig-muted);margin-left:6px;">Due: {{ $task->due_date->format('d M') }}</span>
                                                @endif
                                            </div>
                                            @if($task->status === 'pending')
                                                <form method="POST" action="{{ route('startup.team.tasks.destroy', ['offer' => $offer->id, 'task' => $task->id]) }}" onsubmit="return confirm('Delete this task?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="font-size:10px;color:#ef4444;font-weight:700;background:transparent;border:1px solid #fca5a5;padding:4px 10px;border-radius:8px;cursor:pointer;">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:40px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;">
                            <span style="font-size:36px;">📋</span>
                            <p style="font-size:14px;font-weight:700;color:var(--ig-ink);margin-top:12px;">No tasks created yet</p>
                            <p style="font-size:12px;color:var(--ig-muted);margin-top:4px;">Click "Assign New Task" to start building the mission board.</p>
                        </div>
                    @endforelse
                </div>

                {{-- ═══ TAB: REVIEW CENTER ═══ --}}
                <div x-show="activeTab === 'review'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Review Center</h2>
                    <p style="font-size:12px;color:var(--ig-muted);margin-bottom:16px;">Review submitted work. Approving tasks increases the intern's streak and IPRS score.</p>

                    @php $submittedTasks = $tasks->where('status', 'submitted'); @endphp
                    @forelse($submittedTasks as $task)
                        @php $sub = $task->latestSubmission; @endphp
                        <div style="background:var(--ig-surface);border:1px solid #bfdbfe;border-radius:16px;padding:20px;">
                            <h4 style="font-size:15px;font-weight:700;color:var(--ig-ink);margin-bottom:8px;">{{ $task->title }}</h4>
                            @if($sub)
                                <p style="font-size:12px;color:var(--ig-ink-2);margin-bottom:12px;line-height:1.6;">{{ $sub->description }}</p>
                                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                                    @if($sub->github_url)
                                        <a href="{{ $sub->github_url }}" target="_blank" style="font-size:11px;font-weight:700;color:var(--ig-ink-2);text-decoration:none;">🐙 GitHub</a>
                                    @endif
                                    @if($sub->pull_request_url)
                                        <a href="{{ $sub->pull_request_url }}" target="_blank" style="font-size:11px;font-weight:700;color:#7c3aed;text-decoration:none;">🔀 Pull Request</a>
                                    @endif
                                    @if($sub->demo_url)
                                        <a href="{{ $sub->demo_url }}" target="_blank" style="font-size:11px;font-weight:700;color:var(--ig-accent);text-decoration:none;">🔗 Live Demo</a>
                                    @endif
                                </div>
                                <p style="font-size:10px;color:var(--ig-muted);">Submitted: {{ $sub->submitted_at->format('d M Y, H:i') }}</p>
                            @endif

                            <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">
                                <!-- Approve -->
                                <form method="POST" action="{{ route('startup.team.tasks.approve', ['offer' => $offer->id, 'task' => $task->id]) }}">
                                    @csrf
                                    <button type="submit" style="background:#059669;color:white;font-weight:800;font-size:12px;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;">
                                        ✅ Approve Work
                                    </button>
                                </form>
                                <!-- Request Changes (inline form) -->
                                <div x-data="{ showFeedback: false }">
                                    <button @click="showFeedback = !showFeedback" style="background:transparent;color:#f59e0b;font-weight:700;font-size:12px;padding:10px 20px;border-radius:12px;border:1px solid #fde68a;cursor:pointer;">
                                        🔄 Request Changes
                                    </button>
                                    <div x-show="showFeedback" x-cloak style="margin-top:12px;">
                                        <form method="POST" action="{{ route('startup.team.tasks.request-changes', ['offer' => $offer->id, 'task' => $task->id]) }}" class="space-y-2">
                                            @csrf
                                            <textarea name="feedback" required placeholder="Explain what needs to change..." rows="2"
                                                      style="width:100%;padding:10px 14px;background:var(--ig-bg-2);border:1px solid var(--ig-line-2);border-radius:12px;font-size:12px;color:var(--ig-ink);resize:none;box-sizing:border-box;"></textarea>
                                            <button type="submit" style="background:#f59e0b;color:white;font-weight:700;font-size:11px;padding:8px 16px;border-radius:10px;border:none;cursor:pointer;">Send Feedback</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:40px;background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;">
                            <span style="font-size:36px;">✅</span>
                            <p style="font-size:14px;font-weight:700;color:var(--ig-ink);margin-top:12px;">All caught up!</p>
                            <p style="font-size:12px;color:var(--ig-muted);margin-top:4px;">No submissions waiting for review.</p>
                        </div>
                    @endforelse
                </div>

                {{-- ═══ TAB: WEEKLY REPORTS ═══ --}}
                <div x-show="activeTab === 'reports'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Weekly Reports</h2>
                    @forelse($offer->weeklyReports as $report)
                        <div style="border:1px solid var(--ig-line);border-radius:16px;padding:16px;background:var(--ig-bg-2);">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                <span class="ig-chip ig-chip-accent" style="font-size:8px;font-weight:800;text-transform:uppercase;">Week {{ $report->week_number }}</span>
                                @if($report->rating)
                                    <span style="font-size:12px;font-weight:700;color:#047857;">⭐ {{ $report->rating }}/5</span>
                                @else
                                    <span style="font-size:10px;color:var(--ig-muted);">Not rated</span>
                                @endif
                            </div>
                            <p style="font-size:12px;color:var(--ig-ink);">{{ $report->tasks_completed }}</p>

                            @if(!$report->rating)
                                <form method="POST" action="{{ route('startup.team.reports.feedback', $report->id) }}" style="margin-top:12px;display:flex;gap:8px;align-items:end;flex-wrap:wrap;">
                                    @csrf
                                    <div>
                                        <label style="display:block;font-size:9px;font-weight:700;color:var(--ig-muted);text-transform:uppercase;margin-bottom:2px;">Rating</label>
                                        <select name="rating" required style="padding:6px 10px;border:1px solid var(--ig-line-2);border-radius:8px;font-size:12px;background:var(--ig-surface);color:var(--ig-ink);">
                                            <option value="1">1 ⭐</option><option value="2">2 ⭐</option><option value="3">3 ⭐</option><option value="4" selected>4 ⭐</option><option value="5">5 ⭐</option>
                                        </select>
                                    </div>
                                    <div style="flex:1;min-width:200px;">
                                        <label style="display:block;font-size:9px;font-weight:700;color:var(--ig-muted);text-transform:uppercase;margin-bottom:2px;">Feedback</label>
                                        <input type="text" name="feedback" placeholder="Great work on..." style="width:100%;padding:6px 10px;border:1px solid var(--ig-line-2);border-radius:8px;font-size:12px;background:var(--ig-surface);color:var(--ig-ink);box-sizing:border-box;">
                                    </div>
                                    <button type="submit" style="background:#059669;color:white;font-weight:700;font-size:11px;padding:8px 16px;border-radius:8px;border:none;cursor:pointer;">Rate</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p style="font-size:12px;color:var(--ig-muted);text-align:center;padding:20px;">No reports submitted yet.</p>
                    @endforelse
                </div>

                {{-- ═══ TAB: RESOURCES ═══ --}}
                <div x-show="activeTab === 'resources'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Shared Resources</h2>

                    <!-- Add Resource Form -->
                    <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:16px;padding:16px;">
                        <form method="POST" action="{{ route('startup.team.resources.store', $offer->id) }}" style="display:flex;gap:8px;align-items:end;flex-wrap:wrap;">
                            @csrf
                            <div style="flex:1;min-width:150px;">
                                <label style="display:block;font-size:9px;font-weight:700;color:var(--ig-muted);text-transform:uppercase;margin-bottom:2px;">Title</label>
                                <input type="text" name="title" required placeholder="API Docs" style="width:100%;padding:8px 12px;border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;background:var(--ig-surface);color:var(--ig-ink);box-sizing:border-box;">
                            </div>
                            <div style="flex:2;min-width:200px;">
                                <label style="display:block;font-size:9px;font-weight:700;color:var(--ig-muted);text-transform:uppercase;margin-bottom:2px;">URL</label>
                                <input type="url" name="url" required placeholder="https://..." style="width:100%;padding:8px 12px;border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;background:var(--ig-surface);color:var(--ig-ink);box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:9px;font-weight:700;color:var(--ig-muted);text-transform:uppercase;margin-bottom:2px;">Type</label>
                                <select name="type" required style="padding:8px 12px;border:1px solid var(--ig-line-2);border-radius:10px;font-size:12px;background:var(--ig-surface);color:var(--ig-ink);">
                                    <option value="link">Link</option><option value="github">GitHub</option><option value="figma">Figma</option><option value="document">Document</option><option value="api">API</option>
                                </select>
                            </div>
                            <button type="submit" style="background:var(--ig-accent);color:white;font-weight:700;font-size:11px;padding:8px 16px;border-radius:10px;border:none;cursor:pointer;">+ Add</button>
                        </form>
                    </div>

                    @php $typeIcons = ['link' => '🔗', 'document' => '📄', 'figma' => '🎨', 'github' => '🐙', 'api' => '⚡']; @endphp
                    @forelse($offer->resources as $resource)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--ig-line);">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="font-size:18px;">{{ $typeIcons[$resource->type] ?? '🔗' }}</span>
                                <div>
                                    <a href="{{ $resource->url }}" target="_blank" style="font-size:13px;font-weight:700;color:var(--ig-ink);text-decoration:none;">{{ $resource->title }}</a>
                                    <p style="font-size:10px;color:var(--ig-muted);text-transform:uppercase;">{{ $resource->type }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('startup.team.resources.destroy', ['offer' => $offer->id, 'resource' => $resource->id]) }}" onsubmit="return confirm('Remove?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="font-size:10px;color:#ef4444;font-weight:600;background:transparent;border:none;cursor:pointer;">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p style="font-size:12px;color:var(--ig-muted);text-align:center;padding:20px;">No resources shared yet.</p>
                    @endforelse
                </div>

                {{-- ═══ TAB: ANALYTICS ═══ --}}
                <div x-show="activeTab === 'analytics'" class="space-y-6" style="display:none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Intern Analytics</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @php
                            $totalTasks = $tasks->count();
                            $approvedTasks = $tasks->where('status', 'approved')->count();
                            $completionRate = $totalTasks > 0 ? round(($approvedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Completion Rate</p>
                            <p style="font-size:28px;font-weight:900;color:#059669;margin-top:4px;">{{ $completionRate }}%</p>
                        </div>
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Performance Score</p>
                            <p style="font-size:28px;font-weight:900;color:#0ea5e9;margin-top:4px;">{{ $offer->performance_score }}%</p>
                        </div>
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Highest Streak</p>
                            <p style="font-size:28px;font-weight:900;color:#8b5cf6;margin-top:4px;">{{ $offer->highest_streak ?? 0 }}</p>
                        </div>
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Weekly Reports</p>
                            <p style="font-size:28px;font-weight:900;color:var(--ig-ink);margin-top:4px;">{{ $offer->weeklyReports->count() }}</p>
                        </div>
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Avg Rating</p>
                            <p style="font-size:28px;font-weight:900;color:#f59e0b;margin-top:4px;">{{ round($offer->weeklyReports->avg('rating') ?? 0, 1) }}/5</p>
                        </div>
                        <div style="background:var(--ig-bg-2);border:1px solid var(--ig-line);border-radius:14px;padding:16px;text-align:center;">
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;color:var(--ig-muted);">Total Contributions</p>
                            <p style="font-size:28px;font-weight:900;color:var(--ig-ink);margin-top:4px;">{{ $approvedTasks + $offer->weeklyReports->count() }}</p>
                        </div>
                    </div>
                </div>

                </div>{{-- .ig-card --}}
            </div>{{-- lg:col-span-9 --}}
        </div>{{-- grid --}}
    </div>
</x-app-layout>
