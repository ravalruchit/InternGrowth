<div class="flex flex-col items-center justify-center text-center py-12 px-6 bg-white border border-[var(--ig-line)] rounded-3xl shadow-sm">
    <div class="w-20 h-20 rounded-2xl bg-emerald-50/50 flex items-center justify-center mb-5 text-emerald-600 shadow-inner">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 0M10.5 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5h6" />
        </svg>
    </div>
    <h3 class="font-extrabold text-slate-900 text-lg mb-1">Create your first task</h3>
    <p class="text-xs text-[var(--ig-muted)] max-w-sm leading-relaxed mb-6">
        Post small tasks with custom stipends to test student candidates. Hired students can work on tasks and join your team.
    </p>
    <a href="{{ route('tasks.create') }}" class="ig-btn ig-btn-primary text-xs font-bold px-6 py-2.5 rounded-2xl">
        <span>Post First Task</span>
        <span class="arrow">→</span>
    </a>
</div>
