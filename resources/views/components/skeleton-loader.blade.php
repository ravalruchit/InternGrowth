@props(['type' => 'card', 'count' => 1])

@if($type === 'card')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 0; $i < $count; $i++)
            <div class="ig-card p-6 bg-white border border-[var(--ig-line)] rounded-3xl animate-pulse space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-1/3"></div>
                        <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                    </div>
                </div>
                <div class="space-y-2 pt-2">
                    <div class="h-3 bg-slate-200 rounded w-full"></div>
                    <div class="h-3 bg-slate-200 rounded w-5/6"></div>
                </div>
                <div class="h-10 bg-slate-200 rounded-2xl w-full pt-2"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'list')
    <div class="space-y-4">
        @for($i = 0; $i < $count; $i++)
            <div class="flex items-center gap-4 p-4 bg-white border border-[var(--ig-line)] rounded-2xl animate-pulse">
                <div class="w-10 h-10 rounded-full bg-slate-100"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-3.5 bg-slate-200 rounded w-1/4"></div>
                    <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                </div>
                <div class="w-12 h-4 bg-slate-200 rounded-full"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'chart')
    <div class="ig-card p-6 bg-white border border-[var(--ig-line)] rounded-3xl animate-pulse space-y-6">
        <div class="flex items-center justify-between">
            <div class="h-4 bg-slate-200 rounded w-1/4"></div>
            <div class="h-4 bg-slate-200 rounded w-1/12"></div>
        </div>
        <div class="h-48 bg-slate-50 rounded-2xl flex items-end justify-between p-4 gap-2">
            <div class="w-full bg-slate-200 rounded-t" style="height: 40%;"></div>
            <div class="w-full bg-slate-200 rounded-t" style="height: 65%;"></div>
            <div class="w-full bg-slate-200 rounded-t" style="height: 45%;"></div>
            <div class="w-full bg-slate-200 rounded-t" style="height: 80%;"></div>
            <div class="w-full bg-slate-200 rounded-t" style="height: 95%;"></div>
            <div class="w-full bg-slate-200 rounded-t" style="height: 60%;"></div>
        </div>
    </div>
@endif
